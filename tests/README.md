# Tests

## Usage

To run these scripts manually, run the following from the document root:

```
python3 -m pytest -s
```

## test_url.py

This test will find all fully qualified URLs mentioned in any PHP script and will check if the URL is still alive. This helps weed out dead links from documentation and references.

