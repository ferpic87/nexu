<?php

 $widget = $vars['entity'];
 if (!$widget->folder) {
  return;
 }
?>

<style type="text/css">
/* GDrive */

.gdrive-inline {
 display: inline-block;
 position: relative;
}

.doclist-icon {
    height: 16px !important;
    width: 16px !important;
}

.gdrive-img {
 width:46px;
 height:44px;
}

.img-collection {
 background:url(/mod/gdrive/graphics/collection.png) -24px 0;
 background-repeat: no-repeat;
}

.img-pdf {
 background:url(/mod/gdrive/graphics/icon_10_pdf_list.png) 0 0;
}

.img-word {
 background:url(/mod/gdrive/graphics/icon_10_word_list.png) 0 0;
}

.img-excel {
 background:url(/mod/gdrive/graphics/icon_10_excel_list.png) 0 0;
}

.img-text {
 background:url(/mod/gdrive/graphics/icon_10_text_list.png) 0 0;
}

.img-document {
 background:url(/mod/gdrive/graphics/icon_10_document_list.png) 0 0;
}

.img-spreadsheet {
 background:url(/mod/gdrive/graphics/icon_10_spreadsheet_list.png) 0 0;
}

.img-drawing {
 background:url(/mod/gdrive/graphics/icon_10_drawing_list.png) 0 0;
}

.img-presentation {
 background:url(/mod/gdrive/graphics/icon_10_presentation_list.png) 0 0;
}

.img-form {
 background:url(/mod/gdrive/graphics/icon_10_form_list.png) 0 0;
}

.img-script {
 background:url(/mod/gdrive/graphics/icon_10_script_list.png) 0 0;
}

.img-generic {
 background:url(/mod/gdrive/graphics/icon_10_generic_list.png) 0 0;
}

</style>

<script type="text/javascript">

 function lookup(path) {
  elgg.get('/services/api/rest/json/?method=gdrive.list', {
	data: {
		path: path,
	},
	success: function(json) {
             document.getElementById('content-<?php echo $widget->guid ?>').innerHTML = json.result;
	}
  });
 }

</script>


<?php

 echo '<div id="content-'.$widget->guid.'">'.gdrive_list($widget->folder).'</div>';

