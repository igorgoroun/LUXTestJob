/**
 * JS Test job for vacancy
 *
 * Vacancy decription:
 * http://rabota.ua/company239121/vacancy6463561
 *
 * Test description:
 * https://docs.google.com/document/d/15l2gZrQlMJg4OQthJ7eJaujdbxi4LbR58Vggd2z8H9A/edit#
 *
 * @author Igor <igor.goroun@gmail.com>
 * @version 0.1
 */

$(function(){
    /* initial buttons array */
    var buttons = ['First Button [1]','Second button [2]','Third button [3]'];
    /* template object */
    var tmpl = $.templates("#buttonTemplate");
    /* initial run */
    renderButtons();

    /**
     * Render buttons list
     */
    function renderButtons() {
        /* variable for all buttons list */
        var html = '';
        
        /* create html block from template */
        buttons.forEach(function(item){
            html = html + tmpl.render({'num':item});
        });

        /* place to container */
        $("#buttons-container").html(html);

        /* bind click to created buttons */
        $(".cycle").click(function(){
            buttons.push(buttons.shift());
            renderButtons();
        });
    }
});