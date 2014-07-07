themedyShortcodeMeta={
	attributes:[
		{
			label:"Content",
			id:"content",
			help: 'The content of your info box. Use a &lt;br /&gt; to start a new line.',
			isRequired:true
		},
		{
			label:"Style",
			id:"style",
			help:"Values: &lt;empty&gt;, info, alert, tick, download, note.", 
			controlType:"select-control", 
			selectValues:['', 'info', 'alert', 'check', 'download', 'note', 'rss'],
			defaultValue: '', 
			defaultText: 'none (Default)'
		},
		{
			label:"CSS Class",
			id:"class",
			help:"Optional CSS class."
		}
		],
		defaultContent:"Sample Box Content.",
		shortcode:"box"
};
