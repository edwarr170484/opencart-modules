<?php
class ControllerCatalogGallery extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_gallery->addGallery($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_gallery->editGallery($this->request->get['gallery_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/gallery');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/gallery');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $gallery_id) {
				$this->model_catalog_gallery->deleteGallery($gallery_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}
	
	public function deleteCategory() {
		
		$this->load->model('catalog/gallery');
		
		if (isset($this->request->get['category_id']) && $this->validateDelete()) {
			
			$this->model_catalog_gallery->deleteGalleryCategory((int)$this->request->get['category_id']);

			$this->response->setOutput(json_encode(array('message' => true)));
		}
	}

	protected function getList() {
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/gallery/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/gallery/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['gallerys'] = array();

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$gallery_total = $this->model_catalog_gallery->getTotalGallerys();

		$results = $this->model_catalog_gallery->getGallerys($filter_data);

		foreach ($results as $result) {
			$data['gallerys'][] = array(
				'gallery_id' => $result['gallery_id'],
				'name'      => $result['name'],
				'seo_name'  => $result['seo_name'],
				'status'    => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'      => $this->url->link('catalog/gallery/edit', 'token=' . $this->session->data['token'] . '&gallery_id=' . $result['gallery_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$url = '';

		$pagination = new Pagination();
		$pagination->total = $gallery_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($gallery_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($gallery_total - $this->config->get('config_limit_admin'))) ? $gallery_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $gallery_total, ceil($gallery_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['gallery_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_seo'] = $this->language->get('entry_seo_name');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_banner_add'] = $this->language->get('button_banner_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['seo'])) {
			$data['error_seo'] = $this->error['seo'];
		} else {
			$data['error_seo'] = '';
		}
		
		if (isset($this->error['gallery_image'])) {
			$data['error_gallery_image'] = $this->error['gallery_image'];
		} else {
			$data['error_gallery_image'] = array();
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['gallery_id'])) {
			$data['action'] = $this->url->link('catalog/gallery/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/gallery/edit', 'token=' . $this->session->data['token'] . '&gallery_id=' . $this->request->get['gallery_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/gallery', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['gallery_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$gallery_info = $this->model_catalog_gallery->getGallery($this->request->get['gallery_id']);
			
			$gallery_category = $this->model_catalog_gallery->getGalleryCategory($this->request->get['gallery_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($gallery_info)) {
			$data['name'] = $gallery_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['seo_name'])) {
			$data['seo_name'] = $this->request->post['seo_name'];
		} elseif (!empty($gallery_info)) {
			$data['seo_name'] = $gallery_info['seo_name'];
		} else {
			$data['seo_name'] = '';
		}
		
		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (!empty($gallery_info)) {
			$data['text'] = $gallery_info['text'];
		} else {
			$data['text'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($gallery_info)) {
			$data['status'] = $gallery_info['status'];
		} else {
			$data['status'] = true;
		}
		
		if (isset($this->request->post['categories'])) {
			$data['categories'] = $this->request->post['categories'];
		} elseif (!empty($gallery_info)) {
			$data['categories'] = $gallery_category;
		} else {
			$data['categories'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['gallery_image'])) {
			$gallery_images = $this->request->post['gallery_image'];
		} elseif (isset($this->request->get['gallery_id'])) {
			$gallery_images = $this->model_catalog_gallery->getGalleryImages($this->request->get['gallery_id']);
		} else {
			$gallery_images = array();
		}

		$data['gallery_images'] = array();

		foreach ($gallery_images as $gallery_image) {
			if (is_file(DIR_IMAGE . $gallery_image['image'])) {
				$image = $gallery_image['image'];
				$thumb = $gallery_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['gallery_images'][] = array(
				'gallery_image_description' => $gallery_image['gallery_image_description'],
				'image'                    => $image,
				'category_id'              => $gallery_image['category_id'],
				'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order'               => $gallery_image['sort_order']
			);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['token'] = $this->session->data['token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/gallery_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		/*if (isset($this->request->post['banner_image'])) {
			foreach ($this->request->post['banner_image'] as $banner_image_id => $banner_image) {
				foreach ($banner_image['banner_image_description'] as $language_id => $banner_image_description) {
					if ((utf8_strlen($banner_image_description['title']) < 2) || (utf8_strlen($banner_image_description['title']) > 64)) {
						$this->error['banner_image'][$banner_image_id][$language_id] = $this->language->get('error_title');
					}
				}
			}
		}*/

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/gallery')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}