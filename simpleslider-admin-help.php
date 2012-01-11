<?php

$sss_contextual_help = <<<HELP
<p>The following default options can be set for the Simple Slideshow plugin:</p>

<p><b>Image Size</b>: The dimensions of the images that will be used when displaying a slide show. To adjust these, go 
to the 'Media' control panel, under 'Settings'.</p>

<p><b>Transition Speed</b>: The amount of time, in milliseconds, it will take to fade between individual photos in the slideshow. For a 
quick fade, try entering 150. For a slow fade, try entering 500. Values between 10 and 1000 are valid. </p> 

<p><b>Click Image</b>: If this is enabled, clicking the current sldieshow image will open a new browser window showing the 
full-size version of the image. If this is disabled, clicking the current slideshow image will have no effect.</p>

<p><b>Link Target</b>: If <i>click image</i> is disabled, this option has no effect. If <i>click image</i> is enabled, this option
selects how the full-sized image will be displayed - either the themed Wordpress attachment page will be displayed, or the unstyled 
image directly.</p>

<p><b>Show Counter</b>: Whether or not to show a counter - e.g. <em>1/20</em>, to indicate the user is seeing the first of twenty images - 
below the slideshow.</p> 

<p><b>Cycle Version</b>: The jQuery Cycle version to use. If <em>Lite</em> is selected, the <em>jQuery Cycle Lite</em> (4Kb)
script will be loaded, and only the <em>fade</em> transition will be available. If <em>All</em> is selected, the full <em>jQuery Cycle</em> (30Kb)
script will be loaded, and all transitions will be available. This option is set on a per-site basis only.</p>

<p><b>Transition Effect</b>: The effect to use when transitioning between slides. Anything that can be passed
to the <em>fx</em> parameter of a jQuery Cycle show - listed <a href="http://jquery.malsup.com/cycle/browser.html" target="_new">here</a> - is valid.
If <em>Cycle Version</em> is set to <em>Lite</em>, this value is locked to <em>fade</em>.

<p><b>Auto-advance</b>: If this is enabled, slideshows will advance images automatically (without the user clicking the Prev/Next buttons).</p>

<p><b>Auto-advance Speed</b>: If <i>auto-advance</i> is disabled for a particular slideshow, this option has no effect on that show. If <i>auto-advance</i> is 
enabled for a particular show, this option specifies the amount of time, in milliseconds, that each image will be shown for. 
For a leisurely slideshow, try entering 5000. Values between 1000 and 30000 are valid.</p>


HELP;

$media_uploader = plugins_url( 'images/uploader.png', __FILE__ );
$before_upload = plugins_url( 'images/before_upload.png', __FILE__ );
$after_upload = plugins_url( 'images/after_upload.png', __FILE__ );
$gallery = plugins_url( 'images/gallery.png', __FILE__ );
$close_window = plugins_url( 'images/close.png', __FILE__ );
$code = plugins_url( 'images/code.png', __FILE__ );

$sss_tab_help = <<<DOC
<h3>Executive Summary</h3>
<ol>
<li>Upload images into a post using the <em>media uploader</em>, and set their order on the 
<em>Gallery</em> tab.</li>
<li>Add <code>[simple_slideshow]</code> into the body of your post where you'd like the slideshow.</li>

</ol>

<h3>Detailed Instructions</h3>
<ol>
<li>Bring up the <em>edit</em> screen of the post in which you'd like to insert a slideshow</li>

<li>Click the photo icon to open the <em>media uploader</em>.
<br> 
<img src="${media_uploader}">
</li>

<li>Click <em>select files</em> to pick the photos you'd like to upload and include in the slideshow. Once
they're uploaded, the screen will look like the second photo. Click <em>Save all chages</em> (at the bottom).
<br> 
<img src="${before_upload}" style="padding-right: 10px; margin-right: 0px; vertical-align: top;"><img src="${after_upload}">
</li>

<li>Wordpress will now switch you to the <em>Gallery</em> tab - this is where you select the order the
photos will be in. Drag-and-drop to rearrange their order; the slideshow will start at the top of the list. 
When they're in the order you want, click <em>Save all changes</em> again (and don't worry about anything
in the <em>Gallery Settings</em> section).
<br>
<img src="${gallery}">
</li>

<li><b>Don't</b> click <em>Insert gallery</em> - instead, click the <em>X</em> in the top-right corner of
the window (highlighted below) to get back to editing your post.
<br>
<img src="${close_window}">
</li>

<li>In the body of your post, type <code>[simple_slideshow]</code> where you'd like the slideshow to be. 
Don't worry if you don't see a slideshow appear - it only shows up when you're previewing and publishing the page. 
<br>
<img src="${code}">
</li>

<li>That's it! If you want to tweak your slideshow with some attributes, read the next tab.</li>
</ol>
DOC;

$sss_attribute_tab_help = <<<DOC2
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

<b>show_counter</b> - image counter<br>
Example: <code>[simple_slideshow show_counter="1"]</code><br>
Values: <code>1</code> to enable, <code>0</code> to disable
</p>

<b>show_controls</b> - image controls<br>
Example: <code>[simple_slideshow show_controls="1"]</code><br>
Values: <code>1</code> to enable, <code>0</code> to disable
</p>

<b>transition</b> - transition effect<br>
Example: <code>[simple_slideshow transition="toss"]</code><br>
Values: Any effect listed <a href="http://jquery.malsup.com/cycle/browser.html" target="_new">here</a>.<br>
Note: If <em>Cycle Version</em> on the <em>Settings</em> tab is set to <em>Lite</em>, this attribute is ignored
</p>

<b>auto_advance</b> - auto advance<br>
Example: <code>[simple_slideshow auto_advance="1"]</code><br>
Values: <code>1</code> to enable, <code>0</code> to disable
</p>

<b>auto_advance_speed</b> - image display time<br>
Example: <code>[simple_slideshow auto_advance="1" auto_advance_speed="5000"]</code><br>
Values: any integer between 5000 and 30000, inclusive
</p>
DOC2;

?>