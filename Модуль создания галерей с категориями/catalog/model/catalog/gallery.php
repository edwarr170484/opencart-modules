<?php
class ModelCatalogGallery extends Model {

	public function getGallery($gallery_id, $data = array()) {
		
		if ($data) {
			
			$sql = "SELECT * FROM " . DB_PREFIX . "gallery_image gi INNER JOIN " . DB_PREFIX . "gallery_image_description gid ON (gi.gallery_image_id = gid.gallery_image_id) WHERE gi.gallery_id = '" . (int)$gallery_id . "' ORDER BY gi.sort_order ASC";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}

				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$gallery_image_query = $this->db->query($sql);
			
			return $gallery_image_query->rows;
		} 
		
	}
	public function getGalleryText($gallery_id)
	{
		$query = $this->db->query("SELECT text FROM " . DB_PREFIX . "gallery WHERE gallery_id='" . $gallery_id . "'");
		return $query->row['text'];
	}
	public function getGalleryName($gallery_id)
	{
		$query = $this->db->query("SELECT name FROM " . DB_PREFIX . "gallery WHERE gallery_id='" . $gallery_id . "'");
		return $query->row['name'];
	}
	public function getGalleryCategory($gallery_id)
	{	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "gallery_category WHERE gallery_id='" . $gallery_id . "'");
		return $query->rows;
	}
	public function getTotalGalleryImages($gallery_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "gallery_image WHERE gallery_id='".$gallery_id."'");

		return $query->row['total'];
	}
}