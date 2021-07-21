<?php

require("display.php");
$display = new display();

$tables = $display->get_data();
?>


    <?php foreach ($tables as $table): ?>
<div class="table">
        <table id="<?=$table['title']?>">
            <caption><?=$table['title']?></caption>
            <tr>
                <th>Действия</th>
                <?php foreach ($table['columns'] as $column):?>
                    <th><?=$column?></th>
                <?php endforeach;?>
            </tr>
            <?php foreach ($table['fields'] as $fields => $field):?>
                <tr style="background-color: <?=$field['1. Color']?>">
                    <td>
                        <button data-id="<?= $field['ID']?>" data-table="<?=$table['title']?>" class="delete">Удалить</button>
                        <button data-id="<?= $field['ID']?>" data-table="<?=$table['title']?>" class="recolor">Перекрасить</button>
                        <button data-white="true" data-id="<?= $field['ID']?>" data-table="<?=$table['title']?>" class="recolor">В Белый</button>
                    </td>
                <?php foreach ($field as $key => $value):?>
                    <td class="<?= $key?>"><input type="text" class="editable" data-table="<?=$table['title']?>" data-column="<?= $key?>" value="<?=$value?>" data-id="<?= $field['ID'] ?>" readonly></td>
                <?php endforeach;?>
                </tr>
            <?php endforeach;?>
        </table>
    <button data-table="<?=$table['title']?>" class="add">Добавить ряд</button>
</div>
    <?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    var buts = document.querySelectorAll('.editable');
    var del = document.querySelectorAll('.delete');
    var add = document.querySelectorAll('.add');
    var recolor = document.querySelectorAll('.recolor');

    for (i = 0; i < recolor.length; i++) {
        recolor[i].onclick = function () {
            let id = this.getAttribute("data-id");
            let table = this.getAttribute("data-table");
            let color;

            if(this.getAttribute("data-white") === "true")
                color = "#FFFFFF";
            else{
                color = function getRandomColor() {
                    var letters = '0123456789ABCDEF';
                    var color = '#';
                    for (var i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                }
            }

            $.ajax({
                url: '/core/modules/display/display.php',
                method: 'post',
                dataType: 'html',
                data: {
                    "recolor": {
                        "id": id,
                        "value": color,
                        "table": table
                    }
                },
                success: function (data) {
                    location.reload();
                }
            });
        };
    }

    for (var i = 0; i < buts.length; i++) {
        buts[i].onclick = function(){
            this.removeAttribute("readonly");
        };

        $(buts[i]).on("blur", function() {
            this.setAttribute("readonly", true);
            let table = this.getAttribute("data-table");
            let column = this.getAttribute("data-column");
            let value = this.value;
            let id = this.getAttribute("data-id");

            let data = {
                    "update":{
                        "table": table,
                        "column": column,
                        "value": value,
                        "id": id
                    }
                };

            console.log(data)
            $.ajax({
                url: '/core/modules/display/display.php',
                method: 'post',
                dataType: 'html',
                data: data,
                success: function(data){
                    location.reload();
                }
            });
        });
    }


    for (i = 0; i < del.length; i++) {
        del[i].onclick = function () {
            console.log("del")
            let id = this.getAttribute("data-id");
            let table = this.getAttribute("data-table");

            $.ajax({
                url: '/core/modules/display/display.php',
                method: 'post',
                dataType: 'html',
                data: {
                    "delete": {
                        "id": id,
                        "table": table
                    }
                },
                success: function (data) {
                    location.reload();
                }
            });
        };
    }

    for (i = 0; i < add.length; i++) {
        add[i].onclick = function () {
            let table = this.getAttribute("data-table");

            $.ajax({
                url: '/core/modules/display/display.php',
                method: 'post',
                dataType: 'html',
                data: {
                    "add": {
                        "table": table
                    }
                },
                success: function (data) {
                    location.reload();
                }
            });
        };
    }
</script>