Simple php Flickr Gallery
http://www.flickr.com/services/apps/72157623498770162/
http://phpflickrgallery.mdp3.net/
http://www.gnu.org/copyleft/gpl.html

1) phpFlickrGallery

phpFlickrGallery is a simple php script to automatically create a small gallery
page of photos from a Flickr user's photostream. It is designed to be easily
added to any webpage.

2) Usage

Using Simple php Flickr Gallery on your own website is very easy. If you have a
web server that supports php, all you need to do is include the php file and
the css stylesheet. Then call the function showGallery with a user name and the
number of photos to show on each page.


All styling is done with CSS and can easily be changed, by modifying the
included stylesheet or simply making a new one.

The Gallery structure is kept neat with only a few tags to change.

Basic Structure:
<div class='flickr_gallery'>
  <div class='flickr_gallerynav'>Prev and Next Links</div>
  <div class='flickr_imagebox'>
    <a href="Flickr Photo Page"><img src=""></a>
    <h4>Title</h4>
    <p>Description</p>
  </div>
</div>
<div class='flickr_clear'></div>

3) Modification

With a basic knowledge of php the gallery can be easily modified to use
different tags, or load different sized thumbnails by modifying the function
showGallery. The api key used can also be changed by calling
showGallery($username, $amount_per_page, $apikey) if omitted the default is used.

The api calls are done using a php class I have made called mdp3_flickr that
builds the url and accepts a serialized response based on the api documentation
here

In the future, I would like to build in more api function calls to the main
class, and possibly improve the gallery to show full size images as popups.

Simple php Flickr Gallery is released under a GPL license so feel free to
redistribute.