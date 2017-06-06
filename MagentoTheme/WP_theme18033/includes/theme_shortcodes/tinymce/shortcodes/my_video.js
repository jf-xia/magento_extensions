frameworkShortcodeAtts={
	attributes:[
			{
				label:"File",
				id:"file",
				help:"Enter the url to the video file.<h4>YouTube Video</h4><p>Enter the full url to the video page like this:<br />http://youtube.com/watch?v=3H8bnKdf654</p><h4>Vimeo Video</h4><p>Enter the full url to the video page like this:<br />http://vimeo.com/9679622</p><h4>Self-Hosted Video</h4><p>Enter the full url to the video like this:<br />http://demolink.org/uploads/video.<strong>flv</strong></p>"
			},
			{
				label:"Width",
				id:"width",
				help:"Enter the width of your video."
			},
			{
				label:"Height",
				id:"height",
				help:"Enter the height of your video."
			},
			{
				label:"Player Skin Color",
				id:"color",
				controlType:"select-control",
				selectValues:['black', 'white', 'gray', 'bright_orange', 'orange', 'dark_orange', 'bright_yellow', 'yellow', 'dark_yellow', 'bright_green', 'green', 'dark_green', 'bright_turquoise', 'turquoise', 'dark_turquoise', 'bright_cyan', 'cyan', 'dark_cyan', 'bright_blue', 'blue', 'dark_blue', 'bright_magenta', 'magenta', 'dark_magenta', 'bright_pink', 'pink', 'dark_pink', 'bright_red', 'red', 'dark_red'],
				defaultValue: 'black', 
				defaultText: 'black',
				help:"Choose the color."
			},
			{
				label:"Starting Image",
				id:"image",
				help:"Enter the full URL to the image you'd like to show before the video starts playing. If you're using a video from YouTube or Vimeo you should ignore this option."
			}
			
	],
	defaultContent:"",
	shortcode:"video"
};
