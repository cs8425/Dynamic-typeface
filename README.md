Dynamic-typeface
================

Dynamic generate font data for typeface.js to load.
Reduce size of Chinese fonts.
Only load the needed one.

files
--------
app.js => insert typeface.js's data to MySQL
webfont.sql => DB structure in MySQL
getfont.php => Dynamic generate font data

Usege
-------
#### insert data
change to your font filename in `app.js`
```javascript
var file = __dirname + '/fonts/wenquanyi_micro_hei_regular.typeface.js';
```
and
`nodejs app.js`

#### in html
```html
<script src="path/to/getfont.php?id=[font id]&c=[all characters]"></script>
```
