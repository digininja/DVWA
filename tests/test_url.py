import glob
import re
import requests


def get_php_files():
    patterns = ["*.php", "*/*.php", "*/*/*.php"]
    files = []
    for pattern in patterns:
        files.extend(glob.glob(pattern))
    return files


def get_urls(filename):
    with open(filename, 'r') as f:
        content = f.read()
        matches = re.findall("[\'\"](https?://.*?)[\'\"]", content)
        return matches


def check(url):
    try:
        headers = {
            'User-Agent':
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'
        }
        response = requests.get(url, verify=False, headers=headers)
    except requests.exceptions.ConnectionError:
        return 0
    return response.status_code


def test_url():
    broken_urls = []
    for php_file in get_php_files():
        for url in get_urls(php_file):
            status_code = check(url)
            if status_code not in [200, 300]:
                broken_urls.append((php_file, url, status_code))
    for php_file, url, status_code in broken_urls:
        print("%s\t%s\t%s" % (php_file, url, status_code))

    assert len(broken_urls) == 0, "Broken URLs Detected."
