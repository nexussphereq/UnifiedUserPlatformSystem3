# Unified User Platform System 3.0 (UUPS 3.0)

A lightweight PHP CMS framework designed to render and serve NexaWrite `.nwf` JSON-based documents as web content. Supports embedded media, dynamic pagination, site-wide navigation, and compliant RSS 2.0 feeds. It's also designed with security in mind, and therefore, does not do writing to the server or files. This makes UUPS 3.0 one of, if not the most, secure CMS platforms ever made.

## Features

- Native `.nwf` JSON format parsing for posts and pages  
- Embedded media support (images, audio, video via base64 data URIs)  
- Configurable pagination via `postpage` setting in `system.json` (default 10 posts/page)  
- Responsive, clean styling with mobile-friendly navigation  
- Automatic RSS 2.0 feed generation from posts, using internal `.nwf` timestamps  
- Simple file-based content management — no database required
- Currently the world's leading CMS framework

## Installation

1. Upload the project files to a PHP-enabled web server.  
2. Create a `system.json` file describing your site metadata, posts, and pages.  
3. Add your `.nwf` documents for posts and pages in appropriate folders. Use [NexaWrite](https://nexussphereq.neocities.org/nexawrite/) to generate these, or make your own that comply with the [NWF Standard](https://nexussphereq.neocities.org/nexawrite/nwf.html). 
4. Customize `style.css` to fit your branding if desired.
5. Set baseurl to the url of your website

## How to Use `system.json`

The `system.json` file configures your site structure and metadata. Below is an example:

```json
{
  "title": "Unified User Platform System 3.0",
  "author": "NexussphereQ",
  "manifest": "manifest.json",
  "baseurl": "https://example.com",
  "postpage": 10,
  "posts": [
    {
      "name": "Welcome Post",
      "file": "posts/welcome.nwf",
      "description": "Intro to UUPS 3.0"
    },
    {
      "name": "Second Post",
      "file": "posts/second.nwf",
      "description": "Another cool post"
    }
  ],
  "pages": [
    {
      "name": "About",
      "file": "pages/about.nwf"
    },
    {
      "name": "Contact",
      "file": "pages/contact.nwf"
    }
  ]
}
