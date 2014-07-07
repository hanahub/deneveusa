themedyShortcodeMeta={
	attributes:[
			{
				label:"List Style",
				id:"style",
				help:'Various list bullet styles.<br /><br />When clicking "Insert", a list will be made available for entering list items. Press "ENTER" to create a new item.', 
				controlType:"select-control", 
				selectValues:['check', 'arrow', 'alert', 'rss'],
				defaultValue: 'check', 
				defaultText: 'check (Default)'
			}
			],
	defaultContent:"",
	customMakeShortcode: function(b){
		var a=b.data;
		
		if(!b)return"";
		
		var g = '[list style="' + b.style + '"]<ul>';
		
		g += "<li>" + "Place your list items here" + "</li>"
				
		g += '</ul>[/list] ';
		
		return g
	
	}
};