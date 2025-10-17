'use strict';

const SPINNER = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
const MODAL_SPINNER = '<div class="modal-body text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
const BTN_SPINNER = '<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>'
const BTN_SPINNER_SM = '<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>'

class Main {

	constructor(name) {
		this.$loader = $('#loader')
		this.$modal = $('#JS__modal')
	}

	showAlert(alert) {
		var el = document.createElement("div");
		alert.type = alert.type == 'error' ? 'danger' : alert.type;
		el.className = "alert-fixed alert alert-dismissible alert-" + alert.type;
		el.innerHTML = alert.value + '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>';
		$('body').append(el);
	}

	showLoader() {
		this.$loader.modal('show')
	}
	hideLoader() {
		this.$loader.modal('hide')
	}

	initModals() {
		var self = this
		$(document).on('click', 'a.JS__load_in_modal', function (e) {
			e.preventDefault()
			self.showLoader()

			let size = $(this).data('size') ? 'modal-' + $(this).data('size') : 'modal-md'
			$.ajax({
				url : $(this).attr('href')
			}).done(req => {
				self.$modal.find('.modal-content').html(req)
				if (size) {
					self.$modal.find('.modal-dialog').removeClass(["modal-sm", "modal-lg", "modal-md"]).addClass(size)
				}
				self.$modal.modal('show')
				self.hideLoader()
			})
		})

		$(document).on('submit', '.modal form.JS__load_in_modal', function (e) {
			e.preventDefault()

			self.$modal.find('.modal-content').html(MODAL_SPINNER)

			$.ajax({
				url: $(this).attr('action'),
				method: $(this).attr('method'),
				data: $(this).serialize(),
			})
			.done(req => {
				self.$modal.find('.modal-content').html(req)
				self.$modal.modal('show')
			})
		})
	}


	init() {
		this.initModals()

		$(document).on('click', '.dropdown-menu.dropdown-keep-open', function (e) {
			e.stopPropagation()
		});
		// click
		// $('.btn-icon[data-toggle="tooltip"]').tooltip({trigger:"hover focus"})
		$('[data-toggle="tooltip"]').tooltip({trigger:"hover focus"});

		window.addEventListener('scroll', () => {
			if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
				if (!$('#JS__to_top_btn').is(":visible")) $('#JS__to_top_btn').show();
			} else {
				$('#JS__to_top_btn').hide();
			}
		}, {
			passive: true
		});

		$('#JS__to_top_btn').on('click', () => {
			$("html, body").animate({ scrollTop: 0 }, "slow");
		})
	}

	initTextareaCnt(fieldId) {
		let maxChar = $(fieldId).data('max-char');

		$(fieldId).on('change keydown keyup', function() {
			let cnt = $(this).val().length
			let append = $(this).parents('.form-group').find('.textarea-cnt-hint')
			append.text(cnt + '/' + maxChar + ' Characters');
			if (cnt > maxChar) {
				append.css('color', '#a94442');
			} else {
				append.css('color', '');
			}
		});
		$(fieldId).trigger('change')
	}
}

var main = new Main()
main.init()


class InfinityList {

	constructor(settings) {
		this.FREQUENCY_NEW_REQUEST = 5000;
		this.MAKE_NEW_REQUEST = 60000;

		this.settings = settings;
		this.is_loading = false;
		this.is_end = false;
		this.last_new_request_time = Date.now();

		this.block = $(this.settings.blockQuery);
	}

	init() {
		window.addEventListener('scroll', () => {
			if (this.is_loading) return;

			let rect = this.block.get(0).getBoundingClientRect();
			if (rect.top > 0) {
				this.loadNew();
			}
			if (!this.is_end && rect.bottom - 100 <= (window.innerHeight || document.documentElement.clientHeight)) {
				this.load();
			}
		}, {
			passive: true
		});

		setInterval(() => {
			this.loadNew()
		}, this.MAKE_NEW_REQUEST);
	}

	loadNew() {
		if (this.last_new_request_time > Date.now() - this.FREQUENCY_NEW_REQUEST) {
			return;
		}

		this.last_new_request_time = Date.now();

		this.is_loading = true;
		$(this.settings.loader2Query).html(BTN_SPINNER)

		let maxId = $(this.settings.blockQuery + ' li[data-id]:first-child').data('id');

		$.get(this.settings.url, {
			'maxId' : maxId
		}).then((response) => {
			if (response) {
				$(this.settings.blockQuery).prepend(response);
			}
		}).always(() => {
			this.is_loading = false;
			$(this.settings.loader2Query).html('')
		})
	}

	load() {
		this.is_loading = true;

		$(this.settings.loader1Query).html('<div class="pt-4 pb-2 text-center">' + SPINNER + '</div>')
		let minId = $(this.settings.blockQuery + ' li[data-id]:last-child').data('id');

		$.get(this.settings.url, {
			'minId' : minId
		}).then((response) => {
			if (response) {
				$(this.settings.blockQuery).append(response);
			} else {
				this.is_end = true;
			}
		}).always(() => {
			this.is_loading = false;
			$(this.settings.loader1Query).html('')
		})
	}	

}