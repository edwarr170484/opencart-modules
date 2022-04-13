<?php  echo $header; ?> <?php require( ThemeControlHelper::getLayoutPath( 'common/mass-header.tpl' )  ); ?>
<div class="container">
    
  <?php require( ThemeControlHelper::getLayoutPath( 'common/mass-container.tpl' )  ); ?>
  <div class="row"><?php if( $SPAN[0] ): ?>
			<aside id="sidebar-left" class="col-md-<?php echo $SPAN[0];?>">
				<?php echo $column_left; ?>
			</aside>	
		<?php endif; ?> 
  
   <div id="sidebar-main" class="col-md-<?php echo $SPAN[1];?>"><div id="content"><div class="well">
      <h1><?php echo $heading_title; ?></h1>
	  <div class="gallery-description"><?php echo $text;?></div>
	  
	  <ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#category0" aria-controls="home" role="tab" data-toggle="tab">Все</a></li>
		<?php if($category){?>
			<?php $i = 0;?>
			<?php foreach($category as $cat){ ?>
				<li role="presentation"><a href="#category<?php echo $cat['category_id'];?>" aria-controls="home" role="tab" data-toggle="tab"><?php echo $cat['category_name'];?></a></li>
			<?php $i++;?>	
			<?php }?>
	  <?php }?>
	  	  </ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="category0">
			<div class="gallery-images">
				<ul>
				<?php foreach($images as $image){?>
					<li><a href="/image/<?php echo $image['image']?>" data-lightbox="roadtrip" data-title="<?php echo $image['name']?>. <?php echo $image['description'];?>"><img src="<?php echo $image['thumb']?>" alt="<?php echo $image['name']?>" /></a>
					<div class="image-name"><?php echo $image['name'];?></div>
					</li>
				<?php }?>
				</ul>
			</div>
			</div>
		<?php if($category){?>
			<?php $i = 0;?>
			<?php foreach($category as $cat){ ?>
				 <div role="tabpanel" class="tab-pane" id="category<?php echo $cat['category_id'];?>">
					<?php if($images){?>
					<div class="gallery-images">
						<ul>
							<?php $j = 0; ?>
							<?php foreach($images as $image){?>
								<?php if($image['category_id'] == $cat['category_id'] ){?>
									<li><a href="/image/<?php echo $image['image']?>" data-lightbox="roadtrip" data-title="<?php echo $image['name']?>. <?php echo $image['description'];?>"><img src="<?php echo $image['thumb']?>" alt="<?php echo $image['name']?>" /></a>
									<div class="image-name"><?php echo $image['name'];?></div>
									</li>
								<?php $j++;}?>
							<?php }?>
							<?php if($j == 0) echo 'В категории нет изображений';?>
						</ul>
					<?php } else {?>
						Изображений нет
					<?php }?>
					</div>
				 </div>
			<?php $i++;?>	
			<?php }?>
		</div>
	  <?php }?>
	  </div><?php echo $content_bottom; ?></div>
   </div> 
<?php if( $SPAN[2] ): ?>
	<aside id="sidebar-right" class="col-md-<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</aside>
<?php endif; ?></div>
</div>
<?php echo $footer; ?> 