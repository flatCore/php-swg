---
navigation: Contents
title: php{swg} - Contents
description: Add new Contents to your Static Website
keywords: php, markdown, static, website, builder
---

# Contents

### The Source-folders

One benefit is that you can generate as many sites as you like. Just store your site's files in different Source-folders. If you want to add new Sources, you need simply create a new folder in the php-swg directory and name it soemthing like ```src_*```. All folders whose name starts with ```src_*``` will appear in the selection list for Sources.

### Destination-folders

The procedure for Destination-folders works exactly like Source-folders. Create a folder and name it something like ```static_*``` and it will appear in the selection list for Destinations.

__Note:__ destination folders must have recusrive read and write permissions.

### Add new Contents to your static Website

The easiest way is to apply a markdown (first of all __index.md__) document within the source folder. The file index.md will be converted to the startpage of your site. You can add as many files as you like. The Generator will build the Navigation and Pages automatically.

#### Sort and Structure

If you won‘t get a alphabetic structure, simple prefix your source files with numbers and seperate the file names with two hyphens. Example: ```100--credits.md```. The sorting part in the filename (100--) won't show up in the generated static site - the file will be converted to ```credits.html```. Of course you can also create subfolders. The rules for sorting subfolders are the same.

__Note:__ If you create a subfolder in your source files, it must contain an index.md file.

#### The Markdown files (.md)

The source files are splitted in two sections. The header-informations (at the top, bordered by three dashes) and the mardown code which will be generated into HTML.

Example header:
```
---
navigation: Install
title: Installing php{swg}
description: Install php{swg} on your local- or Webserver
keywords: php, markdown, static, website, builder
---
```


#### _assets - Add images and files to your Project

Create a directory ```_assets``` within your Source folder. The folder ```_assets``` will be copied in your generated static website's root. Use this directory for your Images or Files (e.g. Downloads etc.)

Example usage in your markdown files:
```
![SWG Logo](_assets/images/logo.png)
```
or in a subfolder:
```
![SWG Logo](_assets/images/subfolder/logo.png)
```