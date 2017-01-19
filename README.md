Export (plugin for Omeka)
=============================


Summary
-------

This plugin is an updated version of https://github.com/LibraryofVA/plugin-Export
and as a work in progress the Summary and Usage section below will receive updates
as we proceed.

Two links are added to the Collections/Show page. One link allows for downloading 
a ZIP file containing a single PDF file for each item within the collection and 
a page for each file that has a transcription present. The second link will list 
the original filenames for items within the collection that have a current transcription.
This plugin was designed specifically for use by the Library of Virginia to extract transcription
data to then be imported into a digital asset management system adding full text search capability.

The free PHP library FPDF available from http://www.fpdf.org/ makes the PDF creation
that takes place within this plugin possible.

This plugin is meant to be used in conjuction with Scripto and MediaWiki as orginally
configured as a crowd-sourcing transcription tool by the University of Iowa Libraries
(http://diyhistory.lib.uiowa.edu/transcribe/) and later reproduced by the Library of Virginia
(http://www.virginiamemory.com/transcribe/). Visit https://github.com/LibraryofVA/MakingHistory-transcribe-2.0
for detailed information.

Installation
------------

Uncompress files and rename to "Export".

Ensure your httpd service account has write access to the /plugins/Export/PDF/ directory.

Install it like any other Omeka plugin.


Usage
-----

Two links are added to the Collections/Show page. One link allows for downloading 
a ZIP file containing a single PDF file for each item within the collection and 
a page for each file that has a transcription present. The second link will list 
the original filenames for items within the collection that have a current transcription.

Updates coming soon...


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
