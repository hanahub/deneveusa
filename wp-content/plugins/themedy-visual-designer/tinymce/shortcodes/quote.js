themedyShortcodeMeta={
	attributes:[
		{
			label:"Quote",
			id:"content",
			isRequired:true
		},
		{
			label:"Float",
			id:"float",
			help:"Values: &lt;empty&gt;, left, right.", 
			controlType:"select-control", 
			selectValues:['', 'left', 'right'],
			defaultValue: '', 
			defaultText: 'none (Default)'
		}
		],
		defaultContent:"Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
		shortcode:"quote"
};