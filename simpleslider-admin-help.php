<?php

$sss_contextual_help = <<<HELP
<p>The following default options can be set for the Simple Slideshow plugin:</p>

<p><b>Image Size</b>: The dimensions of the images that will be used when displaying a slide show. To adjust these, go 
to the 'Media' control panel, under 'Settings'.</p>

<p><b>Transition Speed</b>: The amount of time, in miliseconds, it will take to fade between individual photos in the slideshow. For a 
quick fade, try entering 150. For a slow fade, try entering 500. Values between 10 and 1000 are valid. </p> 

<p><b>Click Image</b>: If this is enabled, clicking the current sldieshow image will open a new browser window showing the 
full-size version of the image. If this is disabled, clicking the current slideshow image will have no effect.</p>

<p><b>Link Target</b>: If <i>click image</i> is disabled, this option has no effect. If <i>click image</i> is enabled, this option
selects how the full-sized image will be displayed - either the themed Wordpress attachment page will be displayed, or the unstyled 
image directly.</p>

HELP;

$screenshot_1 = plugins_url( 'screenshot-1.png', __FILE__ );
$screenshot_2 = plugins_url( 'screenshot-2.png', __FILE__ );

$sss_tab_help = <<<DOC
<h3>Usage</h3>
<ol>
<li>Bring up the <em>edit</em> screen of the post in which you'd like to insert a slideshow</li>

<li>Click the photo icon (highlighted below) to open the <em>media uploader</em>, and use that window 
to upload and order all the images you'd like in the slideshow.
<br> 
<img src="${screenshot_1}">
</li>

<li><b>Don't</b> insert the images into the body of your post - instead, type <code>[simple_slideshow]</code> 
into the body of the post where you'd like the slideshow to be. Don't worry if you don't see a slideshow
appear - it only shows up when you're previewing and publishing the page. 
<br>
<img src="${screenshot_2}">
</li>

<li>That's it! If you want to tweak your slideshow with some attributes, read the next section.</li>
</ol>

<h3>Attributes</h3>

<p>Attributes allow you to change how an individual slideshow works; in effect, to override the options
selected on the <em>Settings</em> tab on a per-show basis. If you don't specify an attribute - 
for example, <code>[simple_slideshow]</code> has no attributes specified - the options selected 
on the <em>Settings</em> tab will apply.</p>

<p>You can have more than one custom attribute per slideshow - for example, if you want a show 
to use large-sized images, for each slide to be a link, and for those links to point to the image 
files themselves (no matter what the optsions specified on the <em>Settings</em> tab are), 
you would use <code>[simple_slideshow size="large" link_click="1" link_target="direct"]</code>.</p>

<p> For a fuller explanation of what each of these options change, please click the <em>Help</em>
button located above.</p>

<p>
<b>size</b> - image size<br>
Example: <code>[simple_slideshow size="medium"]</code><br>
Values: <code>thumbnail</code>, <code>medium</code>, <code>large</code>
</p>

<b>transition_speed</b> - transition speed<br>
Example: <code>[simple_slideshow transition_speed="100"]</code><br>
Values: any integer between 10 and 1000, inclusive
</p>

<b>link_click</b> - click image<br>
Example: <code>[simple_slideshow link_click="0"]</code><br>
Values: <code>1</code> to enable, <code>0</code> to disable
</p>

<b>link_target</b> - link target<br>
Example: <code>[simple_slideshow link_target="direct"]</code><br>
Values: <code>direct</code> for the image file, <code>attach</code> for the themed attachment page
</p>
DOC;

?>