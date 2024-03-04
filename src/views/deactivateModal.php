<div class="bitapps-dm-wrapper" id="<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper">
  <div class="bitapps-dm-dialog">
    <div class="bitapps-dm-header">
      <span class="bitapps-dm-header-title">
        <?php echo esc_html__('Quick Feedback', esc_attr($args['slug'])); ?>
      </span>
      <svg class="bitapps-dm-close-svg" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
        <path fill="currentcolor" stroke="currentcolor" stroke-width="2" d="M14.5,1.5l-13,13m0-13,13,13" transform="translate(-1 -1)"></path>
      </svg>
    </div>
    <form class="bitapps-dm-form" method="post">
      <?php wp_nonce_field(esc_attr($args['prefix']) . 'nonce', '_ajax_nonce'); ?>
      <input type="hidden" name="action" value="<?php echo esc_attr($args['prefix']) . 'deactivate_feedback'; ?>" />

      <div class="bitapps-dm-form-caption">
        <?php echo esc_html__('If you have a moment, please let us know how "' . esc_attr($args['title']) . '" can improve.', esc_attr($args['slug'])); ?>
      </div>
      <div class="bitapps-dm-form-body">
        <?php foreach ($args['reasons'] as $reasonKey => $reason) { ?>
          <div class="bitapps-dm-input-wrapper">
            <input id="<?php echo esc_attr($args['slug']) . '-deactivate-feedback-' . esc_attr($reasonKey); ?>" class="bitapps-dm-input" type="radio" name="reason_key" value="<?php echo esc_attr($reasonKey); ?>" required />
            <label for="<?php echo esc_attr($args['slug']) . '-deactivate-feedback-' . esc_attr($reasonKey); ?>" class="bitapps-dm-label"><?php echo esc_html($reason['title']); ?></label>
            <?php if (!empty($reason['placeholder'])) { ?>
              <input class="bitapps-dm-feedback-text" type="text" name="reason_<?php echo esc_attr($reasonKey); ?>" placeholder="<?php echo esc_attr($reason['placeholder']); ?>" />
            <?php } ?>
            <?php if (!empty($reason['alert'])) { ?>
              <div class="bitapps-dm-feedback-text">
                <?php echo esc_html($reason['alert']); ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
      <div class="bitapps-dm-form-footer">
        <button type="submit" class="bitapps-dm-form-submit">
          Submit & Deactivate
        </button>
        <button type="button" class="bitapps-dm-form-skip">
          Skip & Deactivate
        </button>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  (function($) {
    const <?php echo esc_attr($args['prefix']) . 'AdminDialogApp'; ?> = {
      cacheElements: function() {
        this.cache = {
          $deactivateLink: $('#the-list').find('[data-slug="<?php echo esc_attr($args['slug']); ?>"] span.deactivate a'),
          $dialogWrapper: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper'),
          $dialogDialog: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-dialog'),
          $dialogHeader: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-header'),
          $dialogForm: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-form'),
          $dialogSubmit: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-form-submit'),
          $dialogSkip: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-form-skip'),
          $dialogCloseBtn: $('#<?php echo esc_attr($args['slug']); ?>-bitapps-dm-wrapper').find('.bitapps-dm-close-svg'),
          $dialogOpen: false
        }
      },
      bindEvents: function() {
        this.cache.$deactivateLink.on('click', e => {
          e.preventDefault()
          this.showModal()
        })
        this.cache.$dialogForm.on('submit', e => {
          e.preventDefault()
          this.sendFeedback()
        })
        this.cache.$dialogSkip.on('click', e => {
          e.preventDefault()
          this.deactivate()
        })
        this.cache.$dialogCloseBtn.on('click', () => {
          if (this.cache.$dialogOpen) this.hideModal()
        })
        $(document).mouseup(e => {
          if (!this.cache.$dialogOpen) return
          const container = this.cache.$dialogDialog
          if (!container.is(e.target) && container.has(e.target).length === 0) {
            this.hideModal()
          }
        })
        $(document).keyup(e => {
          if (!this.cache.$dialogOpen) return
          if (e.keyCode === 27 && this.cache.$dialogOpen) {
            this.hideModal()
            this.cache.$dialogOpen = false
            this.cache.$deactivateLink.focus()
          }
        })
      },
      deactivate: function() {
        window.location.href = this.cache.$deactivateLink.attr('href')
      },
      hideModal: function() {
        this.cache.$dialogWrapper.hide()
        this.cache.$dialogOpen = false
      },
      showModal: function() {
        this.cache.$dialogWrapper.show()
        this.cache.$dialogOpen = true
      },
      showLoading: function() {
        this.cache.$dialogSubmit.addClass('bitapps-dm-loading')
      },
      hideLoading: function() {
        this.cache.$dialogSubmit.removeClass('bitapps-dm-loading')
      },
      sendFeedback: function() {
        this.showLoading()
        const formData = this.cache.$dialogForm.serialize()

        $.post(ajaxurl, formData, () => {
          this.deactivate()
        }).always(() => {
          this.hideLoading()
        });
      },
      init: function() {
        this.cacheElements()
        this.bindEvents()
      }
    }

    $(function() {
      <?php echo esc_attr($args['prefix']) . 'AdminDialogApp.init()'; ?>
    })
  })(jQuery);
</script>