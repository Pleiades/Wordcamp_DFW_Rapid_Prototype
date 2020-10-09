# Rapid Prototype

**This is NOT a production-ready plugin - it is a collection of sample processes that you can use to customize your own plugin**

Included are samples of:

- Creating Custom Post Types and meta data with relationships to other CPTs
- Importing CSV spreadsheets into CPTs
- Mocking data with online services including: 

[Mockaroo](https://mockaroo.com/)
[ConvertCSV] (convertcsv.com/generate-test-data.htm)
[Faker] (https://github.com/fzaninotto/Faker)
[Loripsum] (https://loripsum.net/)
[Pixabay] (https://pixabay.com/api/docs/)
[MediaWiki] (MediaWiki)
[Pexels] (Pexels)

The code also includes the creation of a custom WP_CLI command as an example, and the addition of various functions and custom Fakers as part of it. 

Run, `wp prototype` to list the available option. 

Options include: 

- wp prototype admin_columns - custom option settings for the Admin Columns plugin
- wp prototype default_nav - creates a navigation menu and adds elements to it
- wp prototype default_pages - generates some default pages with content including shortcodes
- wp prototype default_plugins - installs and activates a customizable list of plugins
- wp prototype default_widgets - adds widgets to specific sidebars and customizes them
- wp prototype erase - removes the data from the post types 
**WARNING this deletes all the data from specified CPTs. Be sure you want to do this!**
- wp prototype erase --all - option to erase to remove all content. 
- wp prototype gardenWeeds - populates a gardenWeeds post type from a CSV
- wp prototype gardening - populates a gardening post type from a CSV
- wp prototype menus - populates a gardenWeeds post type from a CSV
- wp prototype produce - populates a ingredients post type from a CSV
- wp prototype recipes - populates a recipes post type from a CSV
- wp prototype restaurant - generates a full collection of data in the CPTs with linked relationships
- wp prototype spanish - generates pseudo-Spanish text