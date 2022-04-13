<?php
class ModelCatalogGallery extends Model {
	public function addGallery($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "gallery SET name = '" . $this->db->escape($data['name']) . "', seo_name = '" . $this->db->escape($data['seo_name']) . "', text = '" . $this->db->escape($data['text']) . "',status = '" . (int)$data['status'] . "'");
		
		$gallery_id = $this->db->getLastId();

		if (isset($data['gallery_image'])) {
			foreach ($data['gallery_image'] as $gallery_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image SET gallery_id = '" . (int)$gallery_id . "', category_id = '" . (int)$gallery_image['category_id'] . "' ,image = '" .  $this->db->escape($gallery_image['image']) . "', sort_order = '" . (int)$gallery_image['sort_order'] . "'");

				$gallery_image_id = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image_description SET gallery_image_id = '" . (int)$gallery_image_id . "',  gallery_id = '" . (int)$gallery_id . "', title = '" .  $this->db->escape($gallery_image['gallery_image_description']['title']) . "', description = '" . $this->db->escape($gallery_image['gallery_image_description']['description']) . "'");
			}
		}
		
		if(isset($data['categories']))
		{
			foreach($data['categories'] as $category)
			{
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_category SET gallery_id='" . (int)$gallery_id . "', category_name = '" . $category . "'" );
			}
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query='gallery_id=" . (int)$gallery_id . "', keyword='" . $this->db->escape($data['seo_name']) . "'" );
		
		return $gallery_id;
	}

	public function editGallery($gallery_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "gallery SET name = '" . $this->db->escape($data['name']) . "', seo_name = '" . $this->db->escape($data['seo_name']) . "' , text = '" . $this->db->escape($data['text']) . "',status = '" . (int)$data['status'] . "' WHERE gallery_id = '" . (int)$gallery_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image_description WHERE gallery_id = '" . (int)$gallery_id . "'");
		
		if (isset($data['gallery_image'])) {
			foreach ($data['gallery_image'] as $gallery_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image SET gallery_id = '" . (int)$gallery_id . "', category_id = '" . (int)$gallery_image['category_id'] . "' ,image = '" .  $this->db->escape($gallery_image['image']) . "', sort_order = '" . (int)$gallery_image['sort_order'] . "'");

				$gallery_image_id = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_image_description SET gallery_image_id = '" . (int)$gallery_image_id . "',  gallery_id = '" . (int)$gallery_id . "', title = '" .  $this->db->escape($gallery_image['gallery_image_description']['title']) . "', description = '" . $this->db->escape($gallery_image['gallery_image_description']['description']) . "'");
			}
		}
		
		if(isset($data['categories']))
		{
			foreach($data['categories'] as $key => $value)
			{
				$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "gallery_category WHERE category_id='" . $key . "'");
				
				if( $query->row['category_id'] )
				{
					$this->db->query("UPDATE " . DB_PREFIX . "gallery_category SET category_name = '" . $value . "' WHERE category_id = '" . $key . "'" );
				}
				else
				{
					$this->db->query("INSERT INTO " . DB_PREFIX . "gallery_category SET gallery_id='" . (int)$gallery_id . "', category_name = '" . $value . "'" );
				}
			}
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "url_alias SET keyword='" . $this->db->escape($data['seo_name']) . "' WHERE query='gallery_id=" . $gallery_id . "'" );
	}

	public function deleteGallery($gallery_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_image_description WHERE gallery_id = '" . (int)$gallery_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query='gallery_id=" . (int)$gallery_id . "'" );
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_category WHERE gallery_id='" . (int)$gallery_id . "'" );
	}
	
	public function deleteGalleryCategory($category_id)
	{
		$this->db->query("DELETE FROM " . DB_PREFIX . "gallery_category WHERE category_id='" . (int)$category_id . "'" );
	}

	public function getGallery($gallery_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "gallery WHERE gallery_id = '" . (int)$gallery_id . "'");

		return $query->row;
	}
	
	public function getGalleryCategory($gallery_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_category WHERE gallery_id = '" . (int)$gallery_id . "'");

		return $query->rows;
	}

	public function getGallerys($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "gallery";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getGalleryImages($gallery_id) {
		$gallery_image_data = array();

		$gallery_image_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_image WHERE gallery_id = '" . (int)$gallery_id . "' ORDER BY sort_order ASC");

		foreach ($gallery_image_query->rows as $gallery_image) {
			$gallery_image_description_data = array();

			$gallery_image_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_image_description WHERE gallery_image_id = '" . (int)$gallery_image['gallery_image_id'] . "' AND gallery_id = '" . (int)$gallery_id . "'");

			$gallery_image_description_data = array('title' => $gallery_image_description_query->row['title'], 'description' => $gallery_image_description_query->row['description']);

			$gallery_image_data[] = array(
				'gallery_image_description' => $gallery_image_description_data,
				'image'                     => $gallery_image['image'],
				'category_id'               => $gallery_image['category_id'],
				'sort_order'                => $gallery_image['sort_order']
			);
		}

		return $gallery_image_data;
	}

	public function getTotalGallerys() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery");

		return $query->row['total'];
	}
}