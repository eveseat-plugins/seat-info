# seat-info documentation
This is rather a loose collection of information than a guide on how to write and format articles. It should still help 
a bit. Yes, I'm too lazy to write something good. If you have issues, recursivetree#6692 on discord.

## The syntax 
The syntax of the editor is quite similar to html, but it's not exactly the same. It only supports a small subset of 
html listed in this documentation.

## Differences to HTML
 * No kind of header structure required, it's like if you write directly to the body
 * Elements without content have to be closed with a /, like in xhtml: `<img src="..." />`
 * Not all elements are available and some work different
 * URLs in all elements work a bit different, see the section on URL transformers.
 * Escaping characters is done by using a `\ `, e.g. `\<` for a `<` character. I think they are buggy atm tho.

## Attributes supported on all elements
* You can give every element an `id` property.
* Use `text-align="left"`, `text-align="center"` or `text-align="right"` to make the text left-bounded, right-bounded or
centered.

## URL Transformers
To allow easier integration of links to custom targets(other seat info articles,zkill, just to name a few ideas), 
seat-info uses a transformer for all urls. It takes in the url you specified in the markup(e.g. `seatinfo:article/1`) 
and turns it in the real url that links to that page (e.g. `http://my-seat-site.com/info/artice/view/1`). 

The general schema for the url transformers is `type:data`. Type represents the type of the url, e.g. is it a link to 
another article, to another website or to an image. Data is just additional data required for a type to get a working 
url.

For compatibility reasons, when the transformer can't find a valid type, the unprocessed url is returned. This means you 
don't need to change anything after updating, but it will throw a warning.

### Absolute urls
```
url:http://example.com
```

### Relative urls
```
relative:path/to/wherever
```

### Other seatinfo articles
```
seatinfo:article/{id}
seatinfo:article/{id}/{optional comment, e.g. article name}
seatinfo:article/{id}#{hash to jump to a section inside the article}

Examples
seatinfo:article/9
seatinfo:article/9/CorpBuypack
seatinfo:article/9/CorpBuypack#Contacts
```

### Seatinfo resources
```
seatinfo:resource/{id}
seatinfo:resource/{id}/{filename}
seatinfo:resource/9
seatinfo:resource/8/alliancelogo.png
```
### Elements within the same page using an ID
```
id:myIdName
```
Also take a look at the section about links

## Links
`<a href="url:http://example.com">Click me</a>`

`<a href="url:http://example.com" newtab>Click me</a>` Opens it in a new tab

`<a href="seatinfo:article/id">Click me</a>` Opens the article with id. You can find the whole url in the managment tab.

`<a href="seatinfo:resource/id">Click me</a>` Opens a resource uploaded to seat


## Links within the page
You can create links within the article to for example have a table of contents full of links that jump you to the right 
section. To create such a link, add this to jump destination: `<element id="your_id">content</element>`. To actually 
create the link to the target, use a normal link like this: `<a href="#your_id">jump down</a>`.

You aren't restricted to the `pagelink` element anymore, `id` should work on any element. As of right now, it only 
remains as a compatibility element and will be removed in future versions.

## Bold
`<b>bold text</b>`


## Content separation
`<hr />` Same as the html element

The old `<hr>` syntax still works for compatibility reasons, but is deprecated.

## Newline
`<br />`

The old `<br>` syntax still works for compatibility reasons, but is deprecated.


## Titles and Subtitles
`<h1>Main title</h1>`

`<h2>Smaller title</h2>`

`<h3>Even smaller title</h3>`

`<h4>4th subtitle title</h4>`

`<h5>5th subtitle title</h5>`

`<h6>Tiny title</h6>`


## Ordered Lists
```
<ol>
    <li>list entry</li>
    <li>list entry</li>
    <li>list entry</li>
</ol>
```


## Unordered Lists
```
<ul>
    <li>list entry</li>
    <li>list entry</li>
    <li>list entry</li>
</ul>
```


## Paragraph
`<p>text</p>`

## Italic
`<i>text</i>`

## Crossed Text
`<s>crossed text</s>`

## Tables
```
<table stripes border>
    <thead>
        <tr>
            <th>Column1</th>
            <th>Column2</th>
            <th>Column3</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
        </tr>
        <tr>
            <td colspan="2">this thing spans two columns</td>
            <td>3</td>
        </tr>
    </tbody>
</table>
```
Add the argument `stripes` and `borders` to the table to add stripes and borders

## Images
`<img src="link-to-image.png" alt="image description" />`

You can specify a file uploaded to resources: `<img src="seatinfo:resource/id" alt="image description">`
To get the id, go to the management page. Images are put in a new paragraph. If you want the image to be inlined in the 
text, use an icon instead.

The old `<img src="...">` syntax still works for compatibility reasons, but is deprecated.

## Icons
`<icon src="link-to-image.png" alt="icon description" />`

You can specify a file uploaded to resources: `<icon src="seatinfo:resource/id" alt="icon description">`
To get the id, go to the management page. Icons are inlined in the text. If you want them to be on a new line instead, 
use an image tag.

The old `<icon src="...">` syntax still works for compatibility reasons, but is deprecated.

## Colors
```
<color color="#993399">pink</color>
```
Any css color should work.

## Audio
```
<audio src="url" />
```

The old `<audio src="...">` syntax still works for compatibility reasons, but is deprecated.

## Video
```
<video src="url" />
```

## Fits
```
<fit>
[Reaper, Max Range]

Pro-Nav Compact Missile Guidance Enhancer
Type-D Restrained Nanofiber Structure

5MN Quad LiF Restrained Microwarpdrive
Alumel-Wired Enduring Sensor Booster

Arbalest Compact Light Missile Launcher
Arbalest Compact Light Missile Launcher


Acolyte I x1
</fit>
```
Alternatively, you can directly show fits from `seat-fitting` by specifying a `from` attribute.
```
<fit from="seatfitting:fitting/MyFittingName" />
```
Small technical note: You can also point to another url, as long as it returns data in the same format as `InfoController::getFittingPluginFit` it will display the fit.

## Click-To-Copy
When adding the `click-to-copy` attribute to an element, you can click the element to copy the contents to the clipboard. 
In the editor, this functionality is somewhat borken, as clicking an elements also jumps the cursor to the code of an element.