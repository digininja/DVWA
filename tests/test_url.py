import glob
import re
import requests
import time


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


def check_once(url):
    try:
        headers = {
            'User-Agent':
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'
        }
        response = requests.get(url, headers=headers)
    except requests.exceptions.ConnectionError:
        return False, -1
    return response.ok, response.status_code


def check(url):
    # We try for 5 times, with 3 seconds interval.
    try_count = 5
    try_interval = 3
    for i in range(try_count):
        ok, status_code = check_once(url)
        if ok:
            break
        time.sleep(try_interval)
    return ok, status_code


def test_url():
    broken_urls = []
    for php_file in get_php_files():
        for url in get_urls(php_file):
            ok, status_code = check(url)
            if not ok:
                broken_urls.append((php_file, url, status_code))
    for php_file, url, status_code in broken_urls:
        print("%s\t%s\t%s" % (php_file, url, status_code))

    assert len(broken_urls) == 0, "Broken URLs Detected."
