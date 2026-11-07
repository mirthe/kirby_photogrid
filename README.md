# Kirby Plugin: Photogrid

This plugin allows you to place photo's from Flickr on your pages.

## Git submodule

```
git submodule add https://github.com/mirthe/kirby_photogrid.git site/plugins/photogrid
```

## Usage

Add the following to your config:

    'flickr.apiKey' => 'XX',
    'flickr.userID' => 'XX',
    'flickr.username' => 'XX'

You can load the js and css from https://simplelightbox.com/ to have the bigger image appear in a lightbox.

## Example 

Placed for example with a selection of tags:

    (photogrid: tags: Amorphis,Tilburg,20200125,fav)

Or all the photo's in a set:

    (photogrid: set: 123)

To load a single Flickr photo with its photo ID:

    (photogrid: photo: 12345678901)

You can also set the width of a single photo with `pos`. `full` (the default)
keeps the photo at text width, `wide` makes it 30% wider, and `fullwidth` spans
the viewport. The existing `left` and `right` float options are also available:

    (photogrid: photo: 12345678901 pos: full)
    (photogrid: photo: 12345678901 pos: wide)
    (photogrid: photo: 12345678901 pos: fullwidth)
    (photogrid: photo: 12345678901 pos: left)
    (photogrid: photo: 12345678901 pos: right)

To display a hand-picked selection of Flickr photos, separate their photo IDs with
`|` and choose a layout with `pos`. Selection blocks are 30% wider than the text
column, with extra vertical spacing. Clicking a single or selected photo opens
its Flickr page directly; these photos do not use the lightbox.

    (photogrid: selection: 12345678901|12345678902 pos: 1/1)
    (photogrid: selection: 12345678901|12345678902 pos: 1/2)
    (photogrid: selection: 12345678901|12345678902 pos: 2/1)
    (photogrid: selection: 12345678901|12345678902 pos: natural-2)
    (photogrid: selection: 12345678901|12345678902 pos: natural-3)

`1/1` places two equal-width photos side by side. `1/2` and `2/1` make one
column 30% wider than the other. `natural-2` and `natural-3` display two or three
photos per row, with each row's height and photo widths adjusted to the photos'
aspect ratios. This creates a varied, edge-to-edge gallery inspired by the
travelogue. The selected number of photos stays side by side on narrow screens;
they simply become smaller to fit.

<img src="https://github.com/mirthe/kirby_photogrid/blob/4ce378bed479711b283bd766764f97f2895c5a36/example.png" alt="Example of usage">

## Todo

- Offer as an official Kirby plugin
- Might rename to FlickrGrid, or incorporate other services
- Add sample SCSS to this readme
- Cleanup code
- Improve lightbox initialisation
- Add option to display smaller image in grid
- Add title and date
- Lots..
