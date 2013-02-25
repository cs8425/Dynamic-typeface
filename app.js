
var fs = require('fs');
//var file = __dirname + '/fonts/wenquanyi_zen_hei_medium.typeface.js';
//var file = __dirname + '/fonts/helvetiker_regular.typeface.js';
var file = __dirname + '/fonts/wenquanyi_micro_hei_regular.typeface.js';
var mysql = require('mysql');
var connection = mysql.createConnection({
	host:'localhost',
	user: 'webfont',
	password: 'ReHqqPb42X65KjRx',
	database: 'webfont'
});

var check_font_id = function(ok, callback) {
	connection.query('SELECT `font_id`, `familyName` FROM  `font_data` WHERE `familyName` = ' + mysql.escape(ok.familyName), function(err, rows, fields){
		if(err){
			console.log(err);
		}
		if(rows.length > 0){
			console.log('font exist');
			callback(ok, '0');
		}else{
			console.log('font not exist');
			callback(ok, '1');
		}
	});
}

var get_font_id = function(ok, callback) {
	connection.query('SELECT `font_id`, `familyName` FROM  `font_data` WHERE `familyName` = ' + mysql.escape(ok.familyName), function(err, rows, fields){
		if(err){
			console.log(err);
		}
		console.log(rows);
		callback(ok, rows[0].font_id);
	});
}

var inster_font = function(ok, callback) {
	var query = "INSERT INTO  `webfont`.`font_data` (	\
	`familyName` ,	\
	`lineHeight` ,	\
	`underlineThickness` ,	\
	`descender` ,	\
	`resolution` ,	\
	`boundingBox_yMin` ,	\
	`boundingBox_xMin` ,	\
	`boundingBox_yMax` ,	\
	`boundingBox_xMax` ,	\
	`cssFontStyle` ,	\
	`underlinePosition` ,	\
	`ascender` ,	\
	`cssFontWeight`	\
	)	\
	VALUES (	\
	" + mysql.escape(ok.familyName) + ",  \
	" + mysql.escape(ok.lineHeight) + ",  \
	" + mysql.escape(ok.underlineThickness) + ",  \
	" + mysql.escape(ok.descender) + ",  \
	" + mysql.escape(ok.resolution) + ",  \
	" + mysql.escape(ok.boundingBox.yMin) + ",  \
	" + mysql.escape(ok.boundingBox.xMin) + ",  \
	" + mysql.escape(ok.boundingBox.yMax) + ",  \
	" + mysql.escape(ok.boundingBox.xMax) + ",  \
	" + mysql.escape(ok.cssFontStyle) + ",  \
	" + mysql.escape(ok.underlinePosition) + ",  \
	" + mysql.escape(ok.ascender) + ",  \
	" + mysql.escape(ok.cssFontWeight) + "	\
	);";
	connection.query(query,function(err, rows, fields){
		if(err){
			console.log(err);
		}
	});
	callback(ok);
}

fs.readFile(file, 'utf8', function (err, data) {

	if (err) {
		console.log('Error: ' + err);
		return;
	}
	var iLen = String(data).length;
	console.dir(iLen);
	var ok = JSON.parse(data.slice(65, -2));
	//console.dir(ok);

	connection.query('SET NAMES utf8', function(err, rows, fields){
		if(err){
			console.log(err);
			console.log('set encode UTF-8 error');
		}
		//console.log(query);
		check_font_id(ok, function(ok, data){
			console.log(data);
			if(data == '1'){
				inster_font(ok, function(data){
					get_font_id(ok, function(ok, font_id){
						console.log(font_id);
						var maxlen = 0;
						var total = 0;
						var key_error = 0;
						var key_max = 0;
						for(var key in ok.glyphs){
							var len = String(ok.glyphs[key].o).length;
							if(len > maxlen) maxlen = len;
							var query = "INSERT INTO `webfont`.`glyphs` (`font_id`, `char`, `x_min`, `x_max`, `ha`, `o`) VALUES \
							(" + font_id + ", " + mysql.escape( key.charCodeAt(0) ) + ", " + mysql.escape(ok.glyphs[key].x_min) + ", " + mysql.escape(ok.glyphs[key].x_max) + ", " + mysql.escape(ok.glyphs[key].ha) + ", " + mysql.escape(ok.glyphs[key].o) + ");";
							if(ok.glyphs[key].o == null){
								key_error++;
								console.log(key);
							}
							if(key > key_max){
								key_max = key;
							}
							connection.query(query,function(err, rows, fields){
								if(err){
									console.log(err);
								}
							});
							total++;
						}
						console.log('max len: ' + maxlen);
						console.log('total: ' + total);
						console.log('key max: ' + key_max);
						console.log('key error: ' + key_error);
						console.log('all done');
						connection.end();
					});
				});
			}
		});
	});
});


