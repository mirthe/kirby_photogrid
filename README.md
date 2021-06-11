# Kirby Plugin: Photogrid

This plugin allows you to place photo's from Flickr on your pages.

## Git submodule

```
git submodule add https://github.com/mirthe/kirby_photogrid.git site/plugins/photogrid
```

## Usage

Add the following to your config:

    'flickr.apiKey' => 'XX',
    'flickr.userID' => 'XX'

## Example 

Placed for example with 

    (photogrid: tags: Amorphis,013,20200125,fav)

<img src="https://github.com/mirthe/kirby_photogrid/blob/4ce378bed479711b283bd766764f97f2895c5a36/example.png" alt="Example of usage">

## Todo

- Offer as an official Kirby plugin
- Might rename to FlickrGrid, or incorporate other services
- Add sample SCSS to this readme
- Cleanup code
- Add bigger image in lightbox
- Add option to display smaller image in grid
- Add pagination
- Add title and date
- Lots..
