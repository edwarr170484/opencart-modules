<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">Основное</a></li>
            <li><a href="#tab-category" data-toggle="tab">Категории</a></li>
          </ul>
		  <div class="tab-content">
            <div class="tab-pane active in" id="tab-general">
			<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
				  <?php if ($error_name) { ?>
				  <div class="text-danger"><?php echo $error_name; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_seo; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="seo_name" value="<?php echo $seo_name; ?>" placeholder="<?php echo $entry_seo; ?>" id="input-name" class="form-control" />
				  <?php if ($error_seo) { ?>
				  <div class="text-danger"><?php echo $error_seo; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_text; ?></label>
				<div class="col-sm-10">
				  <textarea name="text" id="text" class="form-control note-codable"><?php echo $text; ?></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				  <select name="status" id="input-status" class="form-control">
					<?php if ($status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
          <table id="images" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_title; ?></td>
				<td class="text-left">Категория</td>
                <td class="text-left"><?php echo $entry_description; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $image_row = 0; ?>
              <?php foreach ($gallery_images as $gallery_image) { ?>
              <tr id="image-row<?php echo $image_row; ?>">
                <td class="text-left">
                  <div class="input-group pull-left">
                    <input type="text" name="gallery_image[<?php echo $image_row; ?>][gallery_image_description][title]" value="<?php echo isset($gallery_image['gallery_image_description']) ? $gallery_image['gallery_image_description']['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_gallery_image[$image_row])) { ?>
                  <div class="text-danger"><?php echo $error_gallery_image[$image_row]; ?></div>
                  <?php } ?>
				</td>
				<td class="text-left">
					<select name="gallery_image[<?php echo $image_row; ?>][category_id]" class="form-control">
						<option value="0">нет</option>
						<?php if($categories){?>
							<?php foreach($categories as $category){?>
								<option value="<?php echo $category['category_id'];?>" <?php echo ($gallery_image['category_id'] == $category['category_id']) ? 'selected' : ''; ?>><?php echo $category['category_name'];?></option>
							<?php }?>
						<?php }?>
					</select>
				</td>
                <td class="text-left" style="width: 30%;"><input type="text" name="gallery_image[<?php echo $image_row; ?>][gallery_image_description][description]" value="<?php echo $gallery_image['gallery_image_description']['description']; ?>" placeholder="<?php echo $entry_description; ?>" class="form-control" /></td>
                <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $gallery_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                 <input type="hidden" name="gallery_image[<?php echo $image_row; ?>][image]" value="<?php echo $gallery_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                <td class="text-right"><input type="text" name="gallery_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $gallery_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $image_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
			</div>
			<div class="tab-pane fade" id="tab-category">
				<table id="categoryes" class="table table-striped table-bordered table-hover">
					<thead>
					  <tr>
						<td class="text-left">Название</td>
						<td class="text-right">Действие</td>
					  </tr>
					</thead>
					<tbody>
						<?php if($categories){?>
							<?php foreach($categories as $category){?>
								<tr><td><input class="form-control" name="categories[<?php echo $category['category_id'];?>]" value="<?php echo $category['category_name'];?>"/></td><td><button type="button" onclick="categoryRemove(<?php echo $category['category_id'];?>, $(this))" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td></tr>
							<?php }?>
						<?php }?>
					</tbody>
					<tfoot>
					  <tr>
						<td></td>
						<td class="text-right"><button type="button" onclick="addCategory();" data-toggle="tooltip" title="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					  </tr>
					</tfoot>
				</table>
		    </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  
  function addCategory()
  {
	  $("#categoryes tbody").append("<tr><td><input class=\"form-control\" name=\"categories[]\" placeholder='Название категории'/></td><td></td></tr>");
  }
  
  function categoryRemove(category_id, object)
  {
	  $.ajax({
			url: 'index.php?route=catalog/gallery/deletecategory&category_id=' + category_id + '&token=<?php echo $token; ?>',
			method: 'get',
			dataType: 'json',
			before: function(){
				object.html('<i class="fa-li fa fa-spinner fa-spin"></i>');
			},
			success: function(json) {
				if(json.message)
				{
					object.parent().parent().remove();
				}
			}
		});
  }
  
var image_row = <?php echo $image_row; ?>;
$('#text').summernote({height: 300});

<?php
	
	$cat='';
	
	if($categories)
	{
		foreach($categories as $category)
		{
			$cat .= '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
		}
	}

?>

var category = '<?php echo $cat;?>';

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left">';
	html += '    <div class="input-group">';
	html += '    <input type="text" name="gallery_image[' + image_row + '][gallery_image_description][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" />';
    html += '    </div>';
	html += '  </td>';
	html += '  <td><select name="gallery_image[' + image_row + '][category_id]" class="form-control"><option value="0">нет</option>' + category + '</select></td>';
	html += '  <td class="text-left"><input type="text" name="gallery_image[' + image_row + '][gallery_image_description][description]" value="" placeholder="<?php echo $entry_description; ?>" class="form-control" /></td>';	
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="gallery_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="gallery_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
</script></div>
<?php echo $footer; ?>