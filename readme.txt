=== Wordpress Flash Tag Cloud ===
Contributors: Gljivec, Zdrifko
Donate link: http://premiumcoding.com/wordpress-fyling-tags-plugin/
Tags: categories, cloud, flash, Sphere, tag-cloud, tags, widget, tag, cloud tag, tag cloud, flash tag, flash cloud tag, wordpress Flash Tag Cloud
Requires at least: 2.3
Tested up to: 3.2
Stable tag: 1.13

Wordpress Flash Tag Cloud allows you to display your site's tags, categories or both using fancy Flash animation. 

== Description ==

We present you a premium wordpress plugin which will enhance your webpage. Wordpress Flash Tag Cloud links plugin presents your tags in a creative way with a fun Flash animation. We have prepared a preview of the file that you can see below. Here you can test all the different settings that this plugin offers. A live example of the widget: http://premiumcoding.com/wordpress-fyling-tags-plugin/

== Installation ==

= Installation =
1. Make sure you're running WordPress version 2.3 or better. It won't work with older versions.
1. Download the zip file.
1. Go to your WordPress admin panel and select “Plugins”. 
1. Click on button “Add new” and on next page click upload. Now browse for the zip file "wp-flashflyingtags.zip" mentioned above and click Install now. 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance/Widgets and add your plugin to your sidebar
1. See 'Settings->WP Flash Tag Cloud' to adjust things like display size, etc...

== Frequently Asked Questions ==

= How to change animation type? =
Animation type (Type of Animation). You can choose between five different animations ("staticVertical", "staticHorizontal", "random", "staticVerticalShuffle" and "staticHorizontalShuffle").

= How to change size of widget? =
Set parameters Width and height of tag cloud via settings in Wordpress.

= How to set reflection? = 
Set parameter Reflection to true via settings in Wordpress.

= How to set number of tags? = 
Change parameter Number of tags. 0 means unlimited number of tags (not recommended). Try to keep the number under 50 if you choose random animation.

= My theme/site appears not to like this plugin. It's not displaying correctly. =
There are a number of things that may prevent WP-FlashFlyingTags from displaying or cause it to display a short message about how it needs the Flash plugin.

* In 99% of all cases where this happens the issue is caused by markup errors in the page where the plugin is used. Please validate your blog using [validator.w3.org](http://validator.w3.org) and fix any errors you may encounter.
* Older versions had issues with PHP 5.2 (or better). This has been fixed, so please upgrade to the latest version.
* The plugin requires Flash Player 9 or better and javascript. Please make sure you have both.

= How abut what about SEO? =
I'm not sure how beneficial tag clouds are when it comes to SEO, but just in caseWP-FlashFlyingTags outputs the regular tag cloud (and/or categories listing) for non-flash users. This means that search engines will see the same links. They're hidden through CSS by default, but there's an options to make them visible.

= I'd like to change something in the Flash movie, will you release the .fla? =
Flash version of the file is currently available here: http://activeden.net/item/xml-flying-cloud-links-tags/336878 .If plugins gets a lot of downloads I will make it available for free on Wordpress.

= Some characters are not showing up =
Because of the way Flash handles text, only Latin characters are supported in the current version. This is due to a limitation where in order to be able to animate text fields smoothly the glyphs need to be embedded in the movie. The Flash movie's source code is available for download through Subversion. Doing so will allow you to create a version for your language. There's a text field in the root of the movie that you can use to embed more characters. If you change to another font, you'll need to edit the Tag class as well.

= When I click on tags, nothing happens. =
This is usually caused by a Flash security feature that affects movies served from another domain as the surrounding page. If your blog is http://yourblog.com, but you have http://www.yourblog.com listed as the 'WordPress address' under Settings -> General this issue can occur. In this case you should adjust this setting to match your blog's actual URL. If you haven't already, I recommend you decide on a single URL for your blog and redirect visitors using other options.

== Screenshots ==

1. Blue version
2. Red version
3. White version.
4. Black version.
5. Green version.

== Options ==

The options page allows you to change the Flash movie's dimensions, change the ribbon color as well set the background or make it transparent.

= Width of the Flash tag cloud =
The movie will scale itself to fit inside whatever dimensions you decide to give it. If you make it really small there will be a lot of overlaping and it will be harder to click on them (250X350 is somehow optimal size).

= Background color =
The hex value for the background color you'd like to use. First select Use custom color to true and then update settings. Another field will appear where you can set background color in hexadecimal format.

= Output options =

Choose whether to show tags only, categories only, or both mixed together. Choosing 'both' can result in 'duplicate tags' if you have categories and tags with the same name. These words will appear twice, with one linking to the tag and the other to the category overview.

== Changelog ==

= 1.00 =
* Initial release version.

= 1.02 =
* Fixed an error that caused plugin to malfunction. Please download again if you already have the plugin.

= 1.03 =
* Default number of tags set to 20. All tags generated are in HTML for SEO purposes.

= 1.04 =

* Added shortcode for usage in posts and pages: use [ WP-FlashTagCloud id=1 width=300 height=300 ]:
* For use in code directly use: `<?phpecho wp_tagFlash_short_php(8,300,300);?>`
* ID must be unique, width and height should be numerical (without px)

= 1.05 =

* We have redone Admin panel to be more intuitive, also improved design.

= 1.06 =

* Improved Flash performance and decreased loading time.

= 1.06 =
* Improved Flash performance and decreased loading time

= 1.1 = 
Fixed a XML path related bug

= 1.11 = 
A small bug fix

= 1.12 = 
Added support for latin characters

= 1.13 = 
Improved loading time

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`