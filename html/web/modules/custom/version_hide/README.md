## Security Site

In the .htaccess file, enter the following code if it is not present:

```
# 403 For Development confguration fle
RedirectMatch 403 ^/themes/custom/<THEME_NAME>/gulpfile.js$
RedirectMatch 403 ^/themes/custom/<THEME_NAME>/package.json$

# Disable method TRACE and TRACK method - Http methods
RewriteCond %{REQUEST_METHOD} ^(TRACE|TRACK)
RewriteRule .* - [F]
```
