=== Mason  ===
Contributors: thinkjoinery
Donate link: thinkjoinery.com/about/tripgrass
Tags: acf, block-builder, modules, developer, gutenberg, flexible-content, content-builder, page-builder
Requires at least: 3.0.1
Requires PHP: 5.2
Tested up to: 7.0.10
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mason extends the Advanced Custom Fields Plugin to manage predefined Flexible Content Blocks in a single directory. 

== Description ==

WordPress Page Builder Plugin. Mason is a Page Builder for the high tier Digital Agency, for websites with highly custom designs and functionality. Like any Page Builder, Mason lets you build pages with pre-defined blocks, like hero-style sections with background video support, accordion-style FAQs, slideshows, and testimonials. But that's just the start. Mason's real strength is its flexibility. Each block can be easily customized by the develeopr - the custom field definitions, the javascript, the styles, the views, custom functionality. Each block is completely and intuitively self-contained. What this means is not only can you fork existing blocks, not only can you create new ones, but you can borrow blocks you made on other projects! This means your projects are no longer one off jobs - but they can actually lay the foundation for future projects. With every new project, you'll find your deployment time decreasing as you use your existing (and growing) library of blocks.

== Installation ==

The Module System in the Mason Plugin

Mason uses a custom made module system. We found we were redesigning the same design and functionality for parts of websites over and over - like a full width background video, or a carousel, or a location map. We were rewriting the code for the same "block" a couple times in one website. And then we'd write the same code for a similar block for another website. There clearly had to be a better way. A way where we could reuse the code within a site and borrow it from other sites. So we built our modules system.

A "module" to us is a block on a webpage that is used on multiple pages, or multiple places on the same page. Like a banner with a background image and an <h1> title tag. Or a two-column layout. A "module" is a section that has a very specific style and functionality. Some common modules we use are:

*	"accordion" - which is a list of rows, where each row has a title, and clicking on the title opens a box below the row with content. Imagine an FAQ page.
*	"media" - this is a full width banner block, with a background image or background video and text that overlays (and a filter that sets the opacity over the background image and a color filter over the background image)
*	"slider" - this is a carousel for images
Before we started making "modules" we would build these using the Advanced Custom Fields (ACF) plugin, specifically the "flexible content field". 

You can see the tutorial here for those: https://www.advancedcustomfields.com/resour
ces/flexible-content/
Some blog posts about using flexible content fields if you haven't used them:
http://www.amyhaywood.com/modular-wordpress-theming-flexible-content/
http://www.creativebloq.com/web-design/build-modular-content-systems-wordpress-41514680

The Mason Module System still uses the ACF plugin and the flexible content field, but we code it differently than you may have seen before. 

If you look in /Mason/includes/modules you'll see a sub directory with the name ‘generic_content_test'. This a self-contained "module", containing the same file structure: 

*	_modules.scss
*	functions.php
*	index.php
*	module.js
*	module_layout_acf_def.php
*	module-view.php
*	readme.txt

Let's review these files.

module_layout_acf_def.php this is where you define your acf flexible content field. If you go into the ACF Plugin page in the admin section, you can create the acf fields you need there, and then from the admin->Custom Fields->Tools->Export Field Groups: you select the group you defined and click on the ‘Generate Export Code' button. You can copy and paste from that output the fields you  want to include. Please review an existing module_layout_acf_def.php file for the proper format. You are not adding the entire output from the Generate Export Code, but merely the flexible content definition.

functions.php this, like a WordPress theme, is your custom functions location. Here, you only need to create a function that will query the acf fields (or other data) and create an array of values to pass to the module-view.php (the template). Again, see an example for how this is done. The main goal is to put as much of your "logic" in this file - so that the module-view.php file is mostly HTML and easier to read. If you have javascript associated with this module (which would be located in the module.js file in this subdirectory), you need to enqueue it here.

module-view.php this is the template for your module. The available variables should be listed in the comments at the top of the file. It is permissible to write php logic here (and often necessary), but it should be as little as possible. Basically, only use php to check if the value exists before printing out the HTML.

_modules.scss You will add your styling for this module here.


== Frequently Asked Questions ==
= Plugin will not install =
Esnure ACF is installed and activated

== Screenshots ==


== Changelog ==
= 1.5 =
* Allow Containers to be located on a specific page by title.

= 1.4 =
* Update directory lookup to account for child themes

= 1.3 =
* Extend Global Blocks for all containers

= 1.2 =
* Improved Global Blocks

= 1.1 =
* Added Global Blocks (Clones)

= 1.0 =
* Initial Release.

== Upgrade Notice ==
= 1.5 =
* Allow Containers to be located on a specific page by title.

= 1.4 =
* Update directory lookup to account for child themes

= 1.3 =
* Extend Global Blocks for all containers

= 1.2 =
* Improved Global Blocks

= 1.1 =
* Added Global Blocks (Clones)

= 1.0 =
* Initial Release.