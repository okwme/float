# Float


![upload](https://github.com/okwme/float-photo/blob/master/app/webroot/img/upload.gif?raw=true)
![scroll](https://github.com/okwme/float-photo/blob/master/app/webroot/img/scroll.gif?raw=true)

[Photo Hack Day #4 ](https://photohackday.devpost.com/), Berlin 2015

[Grand Prize Winner](https://www.eyeem.com/blog/the-winning-hacks-from-photo-hack-day-4-berlin/)

built with:
[Alan Woo](https://github.com/alancwoo) & [Nics Kort](https://github.com/n-kort)

(moved to github in 2017)

### Random Notes

#### show and hide the loader:
`showLoader();`
`hideLoader();`

You can also set the loader text like `showLoader('Calculate...')`

#### show and hide the result:
`showResult(topText, bottomText)` such as `showResult('Congratulations', 'You uploaded the worst photo')`


#### check rating
`checkRating(rating)` will check if it's better than the 3rd best (3rd item), or worse than the 3rd worst (4th item)


# TODO

+ poll for change in rating
