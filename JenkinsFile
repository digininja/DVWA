pipeline {
    agent any
    environment {
        DOCKER_IMAGE = "dvwa:${env.GIT_COMMIT}"
        DOCKER_IMAGE_BRANCH = "dvwa:${env.BRANCH_NAME}"
        LOCAL_REGISTRY = "localhost:5000"
        DEPLOY_PORT = (env.BRANCH_NAME == 'prod') ? '8082' : '8081'
        DEPLOY_NETWORK = (env.BRANCH_NAME == 'prod') ? 'prod_net' : 'uat_net'
    }
    options {
        ansiColor('xterm')
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timestamps()
    }
    stages {
        stage('Checkout') {
            steps {
                checkout scm
                container('git') {
                    sh 'git fetch --tags'
                    sh 'git tag -l'
                    sh '''
                    if [ -f vulnerabilities/api/composer.lock ]; then
                        echo "composer.lock found in vulnerabilities/api.";
                    else
                        echo "composer.lock missing in vulnerabilities/api!";
                    fi
                    '''
                }
            }
        }
        stage('Build / Prepare App') {
            steps {
                container('composer') {
                    dir('vulnerabilities/api') {
                        sh '''
                        if [ -f composer.json ]; then
                            composer install --no-interaction
                        fi
                        '''
                    }
                }
            }
        }
        stage('Code Quality') {
            steps {
                container('sonar-scanner') {
                    withSonarQubeEnv('SonarQube') {
                        // Adjust PHPStan/PHPCS to scan files in vulnerabilities/api as needed
                        dir('vulnerabilities/api') {
                            sh '''
                            if [ -f sonar-project.properties ]; then
                                sonar-scanner
                            elif command -v phpstan > /dev/null; then
                                phpstan analyse . || exit 1
                            elif command -v phpcs > /dev/null; then
                                phpcs . || exit 1
                            else
                                echo "No code analysis tools found!"
                                exit 1
                            fi
                            '''
                        }
                    }
                }
            }
        }
        stage('SAST') {
            steps {
                container('semgrep') {
                    // Run Semgrep on vulnerabilities/api or root, depending on your policy
                    sh '''
                    semgrep --config=auto vulnerabilities/api --output semgrep-report.sarif || exit 1
                    '''
                    archiveArtifacts artifacts: 'semgrep-report.sarif', allowEmptyArchive: true
                }
            }
        }
        stage('SCA') {
            steps {
                container('trivy') {
                    sh '''
                    if [ -f vulnerabilities/api/composer.lock ]; then
                        trivy fs vulnerabilities/api --severity CRITICAL --exit-code 1 || exit 1
                    else
                        echo "composer.lock missing in vulnerabilities/api for SCA"
                    fi
                    '''
                    archiveArtifacts artifacts: 'trivy-report.json', allowEmptyArchive: true
                }
            }
        }
        stage('Build Docker Image') {
            steps {
                container('docker') {
                    sh '''
                    docker build --no-cache --pull \
                      --label commit=${GIT_COMMIT} \
                      --label branch=${BRANCH_NAME} \
                      --label build_url=${BUILD_URL} \
                      -t ${DOCKER_IMAGE} \
                      -t ${DOCKER_IMAGE_BRANCH} .
                    '''
                }
            }
        }
        stage('Image Scan') {
            steps {
                container('trivy') {
                    sh '''
                    trivy image --severity CRITICAL --exit-code 1 ${DOCKER_IMAGE}
                    '''
                }
            }
        }
        stage('Push Image') {
            steps {
                container('docker') {
                    sh '''
                    docker tag ${DOCKER_IMAGE} ${LOCAL_REGISTRY}/${DOCKER_IMAGE}
                    docker tag ${DOCKER_IMAGE_BRANCH} ${LOCAL_REGISTRY}/${DOCKER_IMAGE_BRANCH}
                    docker push ${LOCAL_REGISTRY}/${DOCKER_IMAGE}
                    docker push ${LOCAL_REGISTRY}/${DOCKER_IMAGE_BRANCH}
                    '''
                }
            }
        }
        stage('Deploy') {
            when {
                anyOf {
                    branch 'dev'
                    branch 'prod'
                }
            }
            steps {
                container('docker') {
                    script {
                        if (env.BRANCH_NAME == 'prod') {
                            input message: "Manual approval required for PROD deployment"
                        }
                    }
                    sh '''
                    docker network create ${DEPLOY_NETWORK} || true
                    docker-compose -f docker-compose-${BRANCH_NAME}.yml up -d
                    '''
                }
            }
        }
        stage('DAST') {
            steps {
                container('zap') {
                    script {
                        if (env.BRANCH_NAME == 'dev') {
                            sh '''
                            docker run --rm -v $PWD:/zap/wrk -t owasp/zap2docker-stable zap-baseline.py \
                              -t http://localhost:${DEPLOY_PORT} -g gen.conf -r zap_report.html || exit 1
                            '''
                        } else if (env.BRANCH_NAME == 'prod') {
                            sh '''
                            docker run --rm -v $PWD:/zap/wrk -t owasp/zap2docker-stable zap-baseline.py \
                              -t http://localhost:${DEPLOY_PORT} -g gen.conf -r zap_report.html -J -w 2
                            '''
                        }
                    }
                    archiveArtifacts artifacts: 'zap_report.html', allowEmptyArchive: true
                }
            }
        }
        stage('Publish Reports') {
            steps {
                publishHTML([reportDir: '.', reportFiles: 'zap_report.html', reportName: 'ZAP Report'])
                // Add other publishers if needed
            }
        }
    }
    post {
        always {
            container('docker') {
                sh 'docker-compose down || true'
            }
        }
        success {
            echo "Pipeline completed successfully!"
        }
        failure {
            echo "Pipeline failed. Check logs and reports."
        }
    }
}
