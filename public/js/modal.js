$('.button').click(function(){
    var $btn = $(this),
        $step = $btn.parents('.modal-body'),
        stepIndex = $step.index(),
        $pag = $('.modal-header span').eq(stepIndex);
  
    step3($step, $pag);
    
  });
  
  
  function step1($step, $pag){
  console.log('step1');
    // animate the step out
    $step.addClass('animate-out');
    
    // animate the step in
    setTimeout(function(){
      $step.removeClass('animate-out is-showing')
           .next().addClass('animate-in');
      $pag.removeClass('is-active')
            .next().addClass('is-active');
    }, 600);
    
    // after the animation, adjust the classes
    setTimeout(function(){
      $step.next().removeClass('animate-in')
            .addClass('is-showing');
      
    }, 1200);
  }
  
  
  function step3($step, $pag){
  console.log('3');
  
    // animate the step out
    $step.parents('.modal-wrap').addClass('animate-up');
  
    setTimeout(function(){
      $('.rerun-button').css('display', 'inline-block');
    }, 300);
  }
  
  $('.rerun-button').click(function(){
   $('.modal-wrap').removeClass('animate-up')
                    .find('.modal-body')
                    .first().addClass('is-showing')
                    .siblings().removeClass('is-showing');
  
    $('.modal-header span').first().addClass('is-active')
                            .siblings().removeClass('is-active');
   $(this).hide();
  });

  $(function () {
    $("#chkGcash").click(function () {
        if ($(this).is(":checked")) {
            $("#gcash").show();
        } else {
            $("#gcash").hide();
        }
    });
});
$(function () {
  $("#chkPaypal").click(function () {
      if ($(this).is(":checked")) {
          $("#paypal").show();
      } else {
          $("#paypal").hide();
      }
  });
});
$(function () {
  $("#chkCoins").click(function () {
      if ($(this).is(":checked")) {
          $("#coinsph").show();
      } else {
          $("#coinsph").hide();
      }
  });
});
$(function () {
  $("#chkPaymaya").click(function () {
      if ($(this).is(":checked")) {
          $("#paymaya").show();
      } else {
          $("#paymaya").hide();
      }
  });
});

+function($) {
  'use strict';

  var modals = $('.modal.multi-step');

  modals.each(function(idx, modal) {
      var $modal = $(modal);
      var $bodies = $modal.find('div.modal-body');
      var total_num_steps = $bodies.length;
      var $progress = $modal.find('.m-progress');
      var $progress_bar = $modal.find('.m-progress-bar');
      var $progress_stats = $modal.find('.m-progress-stats');
      var $progress_current = $modal.find('.m-progress-current');
      var $progress_total = $modal.find('.m-progress-total');
      var $progress_complete  = $modal.find('.m-progress-complete');
      var reset_on_close = $modal.attr('reset-on-close') === 'true';

      function reset() {
          $modal.find('.step').hide();
          $modal.find('[data-step]').hide();
      }

      function completeSteps() {
          $progress_stats.hide();
          $progress_complete.show();
          $modal.find('.progress-text').animate({
              top: '-2em'
          });
          $modal.find('.complete-indicator').animate({
              top: '-2em'
          });
          $progress_bar.addClass('completed');
      }

      function getPercentComplete(current_step, total_steps) {
          return Math.min(current_step / total_steps * 100, 100) + '%';
      }

      function updateProgress(current, total) {
          $progress_bar.animate({
              width: getPercentComplete(current, total)
          });
          if (current - 1 >= total_num_steps) {
              completeSteps();
          } else {
              $progress_current.text(current);
          }

          $progress.find('[data-progress]').each(function() {
              var dp = $(this);
              if (dp.data().progress <= current - 1) {
                  dp.addClass('completed');
              } else {
                  dp.removeClass('completed');
              }
          });
      }

      function goToStep(step) {
          reset();
          var to_show = $modal.find('.step-' + step);
          if (to_show.length === 0) {
              // at the last step, nothing else to show
              return;
          }
          to_show.show();
          var current = parseInt(step, 10);
          updateProgress(current, total_num_steps);
          findFirstFocusableInput(to_show).focus();
      }

      function findFirstFocusableInput(parent) {
          var candidates = [parent.find('input'), parent.find('select'),
                            parent.find('textarea'),parent.find('button')],
              winner = parent;
          $.each(candidates, function() {
              if (this.length > 0) {
                  winner = this[0];
                  return false;
              }
          });
          return $(winner);
      }

      function bindEventsToModal($modal) {
          var data_steps = [];
          $('[data-step]').each(function() {
              var step = $(this).data().step;
              if (step && $.inArray(step, data_steps) === -1) {
                  data_steps.push(step);
              }
          });

          $.each(data_steps, function(i, v) {
              window.addEventListener('next.m.' + v, function (evt) {
                  goToStep(evt.detail.step);
              }, false);
          });
      }

      function initialize() {
          reset();
          updateProgress(1, total_num_steps);
          $modal.find('.step-1').show();
          $progress_complete.hide();
          $progress_total.text(total_num_steps);
          bindEventsToModal($modal, total_num_steps);
          $modal.data({
              total_num_steps: $bodies.length,
          });
          if (reset_on_close){
              //Bootstrap 2.3.2
              $modal.on('hidden', function () {
                  reset();
                  $modal.find('.step-1').show();
              })
              //Bootstrap 3
              $modal.on('hidden.bs.modal', function () {
                  reset();
                  $modal.find('.step-1').show();
              })
          }
      }

      initialize();
  })
}(jQuery);

sendEvent = function(sel, step) {
  var sel_event = new CustomEvent('next.m.' + step, {detail: {step: step}});
  window.dispatchEvent(sel_event);
}
$(document).on("keyup", ".amountPer", function() {
    var sum = 0;
    $(".amountPer").each(function(){
        sum += +$(this).val();
    });
    $(".total").val(sum);
});
$(document).on('click','.addRow',function(){
    addRow();
  });
  function addRow(){
    var tr= '<tr>'+
            '<td><input type="text" name="walletName[]" class="form-control" required></td>' +
            '<td><input type="text" name="accountName[]" class="form-control" required></td>' +
            '<td><input type="text" name="amountSent[]" class="form-control amountPer" required></td>' + 
            '<td><input type="text" name="referenceNumber[]" class="form-control" required></td>' +
            '<td><a class="btn btn-danger btn-sm removeRow"><i class="fas fa-minus-circle"></i></a></td>' +
            '</tr>';
    $('tbody').append(tr);
  };
  $(document).on('click','.removeRow',function(){
    
    var last = $('tbody tr').length;
    if(last == 1){
        alert("You can not remove last");
    }
    else{
        $(this).closest('tr').remove();
    }
    
});

$('#approveInvestmentRequest').on('show.bs.modal', function (event) {
    var applicant = $(event.relatedTarget);
    var id = applicant.data('req-id');

    document.getElementById("data-id").value=id;
});
$('#rejectInvestmentRequest').on('show.bs.modal', function (event) {
    var applicant = $(event.relatedTarget);
    var id = applicant.data('req-id');

    document.getElementById("reject-id").value=id;
});
$('#update').on('show.bs.modal', function (event) {
    var applicant = $(event.relatedTarget);
    var id = applicant.data('claim-id');

    document.getElementById("updateClaimId").value=id;
});

// $('.addInvestmentForm').on('submit',function() {
//     if (parseInt($('input[name="amount"]').val()) == parseInt($('input[name="total"]').val())) {
//       return true;
//     }
//     else{
//       alert('The amount and total value must be equal.');
//       return false;
//     }
//   });

  $('.expando-list__item').on('click', e => {
    const isOpen = $(e.currentTarget).hasClass('expando-list__item--open');
    
    $('.expando-list__item').removeClass('expando-list__item--open');
    
    if (isOpen) return;
    
    $(e.currentTarget).addClass('expando-list__item--open');
  });