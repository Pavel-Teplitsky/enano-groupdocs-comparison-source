<?php
/**!info**
{
	"Plugin Name"  : "GroupDocs Comparison",
	"Plugin URI"   : "http://groupdocs.com/apps/comparison",
	"Description"  : "GroupDocs Comparison is an online document comparison that lets you compare documents in your browser.",
	"Author"       : "GroupDocs",
	"Version"      : "1.0",
	"Author URI"   : "http://groupdocs.com/"
}
**!*/
/**!install dbms="mysql"; **
CREATE TABLE {{TABLE_PREFIX}}groupdocs_comparison(
  id int(18) NOT NULL auto_increment,
  embed_key varchar(100) NOT NULL DEFAULT 0,
  file_id varchar(100) NOT NULL DEFAULT 0,
  PRIMARY KEY ( id )
) ENGINE=`MyISAM` CHARSET=`UTF8` COLLATE=`utf8_bin`;
 
**!*/
/**!uninstall dbms="mysql"; **
DROP TABLE {{TABLE_PREFIX}}groupdocs_comparison 
**!*/

    $plugins->attachHook('compile_template', 'groupdocs_add_sidebar_comparison();');

    function groupdocs_add_sidebar_comparison() {
       
        global $template, $db;
      
        if (isset($_GET['fileId']) && $_GET['fileId'] != "" && isset($_GET['embedKey']) && $_GET['embedKey'] != "") {
            
            $tagToCopy = "#groupdocsCompare#";
            $embedKey = $db->escape($_GET['embedKey']);
            $fileGuId = $db->escape($_GET['fileId']);
            $check = $db->sql_query('SELECT * FROM ' . table_prefix . "groupdocs_comparison ;");
            $tableCheck = $db->fetchrow($check);
            
            if ($tableCheck == false) {
                
                $q = $db->sql_query('INSERT INTO ' . table_prefix . ' groupdocs_comparison (embed_Key, file_id) VALUES ("' . $embedKey . '","' . $fileGuId . '")');
            
            } else {
                
                $q = $db->sql_query('UPDATE ' . table_prefix . ' groupdocs_comparison SET embed_key = "'.$embedKey.'", file_id = "' . $fileGuId . '"');
                 
                if ($q == false) {
                     
                    $db->_die('Error in DataBase record attempt');
                }
            }
            
        } else {
                     
            $tagToCopy = "";
                    
       }

        $groupForm = '<form name="comparison" action = "' . scriptPath . '/plugins/groupdocscomparison/groupdocscomparison.php" method="POST">

                    <p>Embed Key: <input id="groupdocs_embed_key" name="groupdocs_embed_key" type="text" value="' . $embedKey . '"/></p>
                    <p>File ID: <input id="groupdocs_file_id" name="groupdocs_file_id" type="text" value="' . $fileGuId . '"/></p>
                    <p>Tag to copy: <input id="tag" name="tag" type="text" value="' . $tagToCopy . '"/></p>
                    <input type="submit" id="insert" name="insert" value="Insert" />
                    <br>
                    <a target="blank" href="http://groupdocs.com/docs/display/gendoc/FAQs">See our FAQ</a> to learn how to use Comparison.

                  </form>';

        $template->sidebar_widget('GroupDocs Comparison', $groupForm);
    }

    $plugins->attachHook('render_full', 'gdcompare_hook($carpenter);');

    function gdcompare_hook(&$carpenter) {
        
        $carpenter->hook('comparison', PO_AFTER, 'templates');
    }

    function comparison($text) {
       
        global $db;
        $cmsName = 'Enano';
        $pluginVersion = '1.0';
        
         if (isset($_GET['fileId']) && $_GET['fileId'] != "" && isset($_GET['embedKey']) && $_GET['embedKey'] != "") {
            
            $fileId = $_GET['fileId'];
            $embedKey = $_GET['embedKey'];
        } else {
            $query = $db->sql_query('SELECT embed_key, file_id FROM ' . table_prefix . "groupdocs_comparison ;");
            if ($query == false) {
                   
                $db->_die('Error in DataBase record attempt');
            } else {
                
                $row = $db->fetchrow($query);
                $embedKey = $row['embed_key'];
                $fileId = $row['file_id'];
            
            }
        }
        
        $iframe = '<iframe src="https://apps.groupdocs.com/document-comparison/embed/' . $embedKey . '/' . $fileId . '?&referer=' . $cmsName . '/' . $pluginVersion . '" frameborder="0" width="600" height="400"></iframe>';

        return str_replace('#groupdocsCompare#', $iframe, $text);
    }
?>