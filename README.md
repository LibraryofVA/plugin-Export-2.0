Export (plugin for Omeka)
=============================


Summary
-------

This plugin is an updated version of https://github.com/LibraryofVA/plugin-Export.

Export adds a series of links to the bottom of the Collections/Show page. The links can 
be used to export a single ZIP file containing PDF or Text files organized at the item or file level.
Export was designed specifically for use by the Library of Virginia to extract transcription
data to then be imported into a digital asset management system adding full text search capability.

The free PHP library FPDF available from http://www.fpdf.org/ makes the PDF creation
that takes place within this plugin possible.

This plugin is meant to be used in conjunction with Scripto and MediaWiki as originally
configured as a crowd-sourcing transcription tool by the University of Iowa Libraries
(http://diyhistory.lib.uiowa.edu/transcribe/) and later reproduced by the Library of Virginia
(http://www.virginiamemory.com/transcribe/). Visit https://github.com/LibraryofVA/MakingHistory-transcribe-2.0
for detailed information.

Installation
------------

Uncompress files and rename to "Export".

Ensure your httpd service account has write access to the /plugins/Export/PDF/ and /plugins/Export/TXT/ directories.

Install it like any other Omeka plugin.


Usage
-----

An Export section is added to the bottom of the Collections/Show page.
- Download ZIP file containing a transcription pdf for each item (collection->item)
- Download ZIP file containing a transcription pdf for each file (collection->item->file)
- Download ZIP file containing a transcription .txt file for each file (collection->item->file)
- View the original file names from this collection (collection->item->file)


Warning
-------

Use it at your own risk.

It's always recommended to backup your database so you can roll back if needed.


License
-------

This plugin is published under [GNU/GPL](https://www.gnu.org/licenses/gpl-3.0.html).

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.


Contact
-------

Current maintainers:
Library of Virginia

Copyright
---------

Copyright Library of Virginia, 2017
