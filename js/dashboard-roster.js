/**
 * Created by olajuwon on 2/10/2015.
 */
(function ($){

    Roster = {
        init: function(){
            $('#dashboard-calendar').fullCalendar({
                header: {
                    left: 'today',
                    center: 'title',
                    right: 'basicWeek, basicDay'
                },
                defaultView: 'basicWeek',
                height: 125,
                eventLimit: true, // allow "more" link when too many events
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar
                events:{
                    url: host + 'phase/admin/phase_roster.php?intent=getStaffRoster'
                },
                loading: function(bool) {
                    $('#roster_loading').toggle(bool);
                }
            });
            //*Legend */
            //var content = '<div class="text-center" id="roster-legend">' +
            //                '<span class="m-duty small">Morning</span>' +
            //                '<span class="a-duty small">Afternoon</span>' +
            //                '<span class="n-duty small">Night&nbsp;&nbsp;&nbsp;</span><br/>' +
            //              '</div>';
            //$('.fc-toolbar').append(content)
            //console.log((new Date()).toUTCString());
        }
    };
    $(function(){
        Roster.init();
    })
})(jQuery);

