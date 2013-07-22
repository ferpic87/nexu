<div>
    <label>Folder ID:</label> 
<?php 
    //This is an instance of the ElggWidget class that represents our widget.
    $widget = $vars['entity'];
 
    // Give the user a plain text box to input a message
    echo elgg_view('input/text', array(
        'name' => 'params[folder]', 
        'value' => $widget->folder,
        'class' => 'gdrive-input-text',
    )); 
?>
</div>