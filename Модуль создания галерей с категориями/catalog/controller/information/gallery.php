<?php
class ControllerInformationGallery extends Controller {
	var $limit = 10000;
	public function index() {
		$this->load->language('information/gallery');

		$this->load->model('catalog/gallery');

		$this->load->model('tool/image');
		
		if (isset($this->request->get['gallery_id'])) {
			$gallery_id = $this->request->get['gallery_id'];
		} else {
			$gallery_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$filter_data = array(
				'start'              => ($page - 1) * $this->limit,
				'limit'              => $this->limit
		);
		
		$gallery = $this->model_catalog_gallery->getGallery($gallery_id, $filter_data);

		if ($gallery) {
			
			$gallery_name = $this->model_catalog_gallery->getGalleryName($gallery_id);
			
			$data['breadcrumbs'][] = array(
				'text' => $gallery_name,
				'href' => $this->url->link('information/gallery', 'gallery_id=' . $gallery_id)
			);
			
			foreach($gallery as $image)
			{
				$data['images'][] = array(
					'image_id'    => $image['gallery_image_id'],
					'category_id' => $image['category_id'],
					'thumb'       => $this->model_tool_image->resize($image['image'], 200, 200),
					'image'       => $image['image'],
					'name'        => $image['title'],
					'description' => html_entity_decode($image['description'], ENT_QUOTES, 'UTF-8')
				);
			}
			
			$gallery_category = $this->model_catalog_gallery->getGalleryCategory($gallery_id);
			
			$data['category'] = array();
			
			if($gallery_category)
			{
				foreach($gallery_category as $category)
				{
					$data['category'][] = array(
						'category_id'         => $category['category_id'],
						'category_name'       => $category['category_name']
					);
				}
			}
		
			$this->document->setTitle($gallery_name);
			$this->document->setDescription($gallery_name);
			$this->document->setKeywords($gallery_name);

			$data['heading_title'] = $gallery_name;
			$data['text'] = html_entity_decode($this->model_catalog_gallery->getGalleryText($gallery_id));
			
			$gallery_total = $this->model_catalog_gallery->getTotalGalleryImages($gallery_id);

			$url = '';

			$pagination = new Pagination();
			$pagination->total = $gallery_total;
			$pagination->page = $page;
			$pagination->limit = $this->limit;
			$pagination->url = $this->url->link('information/gallery', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_total) ? (($page - 1) * $this->limit) + 1 : 0, ((($page - 1) * $this->limit) > ($gallery_total - $this->limit)) ? $gallery_total : ((($page - 1) * $this->limit) + $this->limit), $gallery_total, ceil($gallery_total / $this->limit));

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/gallery.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/gallery.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/information/gallery.tpl', $data));
			}
		} else {
			$url = '';

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}