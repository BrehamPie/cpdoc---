<?php
include './includes/header.inc.php';
?>
<style>
    table.fixed {
        table-layout: fixed;
        width: 85vw;
    }

    /*Setting the table width is important!*/
    table.fixed td {
        overflow: hidden;
    }

    /*Hide text outside the cell.*/
    table.fixed td:nth-of-type(1) {
        width: 5vw;
    }

    table.fixed th:nth-of-type(1) {
        width: 5vw;
    }

    /*Setting the width of column 1.*/
    table.fixed td:nth-of-type(2) {
        width: 40vw;
    }

    table.fixed th:nth-of-type(2) {
        width: 40vw;
    }

    /*Setting the width of column 2.*/
    table.fixed td:nth-of-type(3) {
        width: 15vw;
    }

    table.fixed th:nth-of-type(3) {
        width: 15vw;
    }

    table.fixed td:nth-of-type(4) {
        width: 25vw;
    }

    table.fixed td:nth-of-type(4) {
        width: 25vw;
    }

    /*Setting the width of column 3.*/
</style>
<main>
    <div class="container-fluid col-11">
        <div class="judge-name mt-3">
            <h4 class="text-center"><img src="./assets/cf_logo.svg" alt="" height="30px"></h4>
            <table class="table table-hover text-center fixed " id="cftable">
                <thead>

                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">Time Before Contest</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="judge-name mt-3">
            <h4 class="text-center"><img src="./assets/atc_logo.png" alt="" height="90px"></h4>
            <table class="table table-hover text-center fixed " id="atctable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">Time Before Contest</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="judge-name mt-3">
            <h4 class="text-center"><img src="./assets/cc_logo.png" alt="" height="90px"></h4>
            <table class="table table-hover text-center fixed " id="cctable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">Time Before Contest</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="judge-name mt-3">
            <h4 class="text-center"><img src="./assets/toph_logo.png" alt="" height="90px"></h4>
            <table class="table table-hover text-center fixed " id="tophtable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Start Time</th>
                        <th scope="col">Time Before Contest</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function getFromAPI(url, callback) {
        var obj;
        fetch(url)
            .then(res => res.json())
            .then(data => obj = data)
            .then(() => callback(obj))
    }
    getFromAPI('https://kontests.net/api/v1/all', getToph);

    function dhm(ms) {
        if (ms < 0) return "Running";
        const days = Math.floor(ms / (24 * 60 * 60 * 1000));
        const daysms = ms % (24 * 60 * 60 * 1000);
        const hours = Math.floor(daysms / (60 * 60 * 1000));
        const hoursms = ms % (60 * 60 * 1000);
        const minutes = Math.floor(hoursms / (60 * 1000));
        if (minutes < 10) adm = '0';
        else adm = '';
        const minutesms = ms % (60 * 1000);
        const sec = Math.floor(minutesms / 1000);
        if (sec < 10) ads = '0';
        else ads = '';
        return days + " Days " + hours + " Hours " + adm + minutes + " Minutes " + ads + sec + " Seconds";
    }
    function getToph(arrOfObjs) {
        var res = JSON.parse(JSON.stringify(arrOfObjs));
        var tophContest = res.filter(e => e['site'] == 'Toph');
        var cfContest = res.filter(e => e['site'] == 'CodeForces');
        var ccContest = res.filter(e => e['site'] == 'CodeChef');
        var atcContest = res.filter(e => e['site'] == 'AtCoder');
        fillTable(tophContest, 'tophtable');
        fillTable(cfContest, 'cftable');
        fillTable(ccContest, 'cctable');
        fillTable(atcContest, 'atctable');
    }

    function fillTable(res, tableid) {
        var startTimeArray = [];
        var count = 1;
        var curTime = new Date();
        res.forEach(e => {
            var curl = e['url'];
            var startTime = new Date(e['start_time']);
            var fullDate = startTime.getDate() + '/' + (startTime.getMonth() + 1) + '/' + startTime.getFullYear();
            var Hour = startTime.getHours();
            var ampm = "AM";
            if (Hour > 12) {
                ampm = "PM";
                Hour -= 12;
            }
            if (Hour == 0) Hour = 12;
            var fullTime = Hour + ':' + startTime.getMinutes() + ':' + startTime.getSeconds() + '0 ' + ampm;
            var Remaining = startTime - curTime;
            if (Remaining > 0 && count < 6) {
                startTimeArray.push(startTime);
                var contestName = e['name'];
                var Row = '<tr><th scope="row">' + count + '</th> <td scope="col"><a href="'+curl+'">' + contestName + '</a></td><td scope="col">' + '<p>' + fullTime + '</p>' + ' ' + fullDate + '</td><td scope="col">' + dhm(Remaining) + '</td></tr>';
                var tableRef = document.getElementById(tableid).getElementsByTagName('tbody')[0];
                var newRow = tableRef.insertRow(tableRef.rows.length);
                newRow.innerHTML = Row;
                count++;
            }
        });
        // Update the count down every 1 second
        var P = 0;
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();
            var table = document.getElementById(tableid);
            for (let i in table.rows) {
                if (i == 0) continue;
                if (i == 'length') break;
                let row = table.rows[i];
                //console.log(i,row.cells[0],row.cells[1],row.cells[2]);
                row.cells[3].innerHTML = dhm(startTimeArray[i - 1] - now);
                P++;
            }
        }, 500);
    }
</script>