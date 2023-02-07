// moment.js necesario
$("body").on('focus', ".date", function(e){
    var self = $(this);

    if(! self.data('datepicker')){
      self.datepicker({
        language: 'es',
        dateFormat: 'yyyy-mm-dd',
        toggleSelected:false,
        autoClose:true,
      });

      if(self.val().length > 0)
      {
        dp = self.data('datepicker');
        var dtAux = moment(self.val());
        dp.selectDate(new Date(dtAux));
      }
    }
}).on('change', ".date", function(e){
  $(this).data('datepicker').destroy();
});

$("body").on('focus', ".datetime", function(e){
    var self = $(this);

    if(! self.data('datepicker')){
      self.datepicker({
        language: 'es',
        dateFormat: 'yyyy-mm-dd',
        timepicker:true,
        timeFormat: 'hh:ii:00',
        toggleSelected:false,
        autoClose:true,
      });

      if(self.val().length > 0){
        dp = self.data('datepicker');
        dp.selectDate(new Date(self.val()));
      }
    }
}).on('change', ".datetime", function(e){
  $(this).data('datepicker').destroy();
});