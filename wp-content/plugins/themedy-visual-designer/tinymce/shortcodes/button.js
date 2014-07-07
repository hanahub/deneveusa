themedyShortcodeMeta={
	attributes:[
			{
				label:"Title",
				id:"content",
				help:"The button title.",
				isRequired:true
			},
			{
				label:"Link",
				id:"link",
				help:"Optional link (e.g. http://google.com).",
				validateLink:true
			},
			{
				label:"Size",
				id:"size",
				help:"Values: &lt;empty&gt; for normal size, small, large, xl.", 
				controlType:"select-control", 
				selectValues:['small', '', 'large', 'xl'],
				defaultValue: '', 
				defaultText: 'medium (Default)'
			},
			{
				label:"Style",
				id:"style",
				help:"Values: &lt;empty&gt;, info, alert, tick, download, note.",
				controlType:"select-control", 
				selectValues:['', 'info', 'alert', 'check', 'download', 'note', 'rss', 'twitter'],
				defaultValue: '', 
				defaultText: 'none (Default)'
			},
			{
				label:"Color",
				id:"color",
				help:'Use one of our predefined styles.', 
				controlType:"select-control", 
				selectValues:['silver', 'red', 'orange', 'green', 'aqua', 'teal', 'purple', 'pink', 'blue', 'black'],
				defaultValue: '', 
				defaultText: 'none (Default)'
			},
			{
				label:"CSS Class",
				id:"class",
				help:"Optional CSS class."
			},
			{
				label:"Open in a new window",
				id:"window",
				help:"Optionally open this link in a new window.", 
				controlType:"select-control", 
				selectValues:['', 'new'],
				defaultValue: '', 
				defaultText: 'no (Default)'
			}
			],
	defaultContent:"Button",
	shortcode:"button"
};
