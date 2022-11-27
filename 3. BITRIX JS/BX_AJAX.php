<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

// Подключаем ядро Bitrix JS и одно расширение 'ajax';
   CJSCore::Init(array('ajax'));
// присваиваем переменной название формы
   $sidAjax = 'testAjax';
//проверяем в глобальной переменной есть ли данные формы testAjax
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
//сообщаем что не надо показывать шапку и футер в ответе
   $GLOBALS['APPLICATION']->RestartBuffer();
// преобразуем массив PHP в объект JS
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
//выходим
   die();
}

?>
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>
<script>
//включаем дебаг режим, что бы можно было в консоле смотреть
   window.BXDEBUG = true;
function DEMOLoad(){
//   скрываем блок с id="block"
   BX.hide(BX("block"));
// позываем блок с id="process"
   BX.show(BX("process"));
//делаем запрос и передаем полученный результат в фукцию DEMOResponse
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}
function DEMOResponse (data){
// смотрим в консоле, что пришло в data
   BX.debug('AJAX-DEMOResponse ', data);
//вставляем блоку с id="block" содержимое объекта data, значение по ключу RESULT
   BX("block").innerHTML = data.RESULT;
//показываем block
   BX.show(BX("block"));
//скрываем process
   BX.hide(BX("process"));
//вызываем обработчик события DEMOUpdate для блока block
   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}
//когда страница загружена
BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
//скрываем block и process
   BX.hide(BX("block"));
   BX.hide(BX("process"));
//передаем управление на событие клика на кнопке css_ajax
    BX.bindDelegate(
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
         //если не наше событие - уходим
            e = window.event;
         // если наше (клик по кнопке) передаем управление в функцию DEMOLoad()
         DEMOLoad();
         //запрещаем перезагрузку страницы
         return BX.PreventDefault(e);
      }
   );
   
});

</script>
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
