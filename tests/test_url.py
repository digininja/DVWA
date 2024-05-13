import glob
import re
import requests
import time


def get_php_files():
    patterns = ["*.php", "*/*.php", "*/*/*.php"]
    files = []
    ignore_files = ["dvwa/includes/Parsedown.php"]
    for pattern in patterns:
        files.extend(glob.glob(pattern))
    for ignore_file in ignore_files:
        if ignore_file in files:
            files.remove(ignore_file)
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
    # Need to rewrite this so it generates a single, unique list of URLs,
    # removes any which are to be ignored, and then checks them. Would be
    # much cleaner.
    
    ignore_urls = [
        "https://wpscan.com/", # Cloudflare doesn't like GitHub checking it
        "http://www.w3.org/TR/html4/loose.dtd", # Don't need to check the DTD
        "https://www.vmware.com/", # Throwing a 403 for some reason, but can't see it going anywhere
        "https://twitter.com/digininja", # Twitter doesn't like GitHub checking it
        "https://www.cgisecurity.com/xss-faq.html", # Throwing a 403 for some reason, but can't see it going anywhere
        "https://www.cgisecurity.com/csrf-faq.html", # Throwing a 403 for some reason, but can't see it going anywhere
    ]
    all_urls = []
    broken_urls = []
    for php_file in get_php_files():
        for url in get_urls(php_file):
            all_urls.append(url)
    
    # This removes any duplicates
    dedup_urls = list(dict.fromkeys(all_urls))

    for url in dedup_urls:
        if not url in ignore_urls:
            # print("checking %s" % url)
            ok, status_code = check(url)
            if not ok:
                # The php_file variable is now broken as it was set in a previous loop
                # and doesn't come across into this one.

                #print("failed to access %s from file %s with code %d" % (url, php_file, status_code))
                # broken_urls.append((php_file, url, status_code))
                broken_urls.append((url, status_code))

    #for php_file, url, status_code in broken_urls:
    #    print("%s\t%s\t%d" % (php_file, url, status_code))

    for url, status_code in broken_urls:
        print("%s\t%d" % (url, status_code))

    assert len(broken_urls) == 0, "Broken URLs Detected."
