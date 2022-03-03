<!--https://www.d3-graph-gallery.com/graph/pie_basic.html-->
<!--https://www.d3-graph-gallery.com/graph/pie_annotation.html-->

<!-- php -S localhost:8000 -t a3-experiment -->

<!DOCTYPE html>
<html>
<meta charset="utf-8">

<!-- Load d3.js -->
<script src="https://d3js.org/d3.v4.min.js"></script>

<style>
  svg {
    display: block;
    margin-bottom: 5px;
  }
  body {
    font-family: Tahoma, sans-serif;
  }
  .bar {
            fill: rgb(223, 82, 17);
        }

</style>

<body>
  
  <h1> Experiment </h1>
  <p> Description/Intro </p> <br>

  <form method="post">

  <h3> Graph 1: Pie Chart</h3>
  <p>This pie chart is comparing the deaths per 100,000 between genders as well as showning the total.</p>
  <div id="pie_chart"></div>
  <p>Do you think this graph is misleading?</p>
  <input type="radio" id="yes1" name="yes_no1" value="Yes">
  <label for="yes1">Yes</label><br>
  <input type="radio" id="no1" name="yes_no1" value="No">
  <label for="no1">No</label><br><br>
  <label for="explain1">Why or why not?:</label><br><br>
  <input type="text" id="explain1" name="explain1" value="" size="100"><br>
  <br><br>

  <h3> Graph 2: Bar Chart </h3>
  <svg id="svg2" width="600" height="500"></svg>
  <p>Do you think this graph is misleading?</p>
  <input type="radio" id="yes2" name="yes_no2" value="Yes">
  <label for="yes2">Yes</label><br>
  <input type="radio" id="no2" name="yes_no2" value="No">
  <label for="no2">No</label><br><br>
  <label for="explain2">Why or why not?:</label><br><br>
  <input type="text" id="explain2" name="explain2" value="" size="100"><br>
  <br><br>

  <h3> Graph 3 </h3>
  <svg id="svg3"></svg>
  <p>Do you think this graph is misleading?</p>
  <input type="radio" id="yes3" name="yes_no3" value="Yes">
  <label for="yes3">Yes</label><br>
  <input type="radio" id="no3" name="yes_no3" value="No">
  <label for="no3">No</label><br><br>
  <label for="explain3">Why or why not?:</label><br><br>
  <input type="text" id="explain3" name="explain3" value="" size="100"><br>
  <br><br><p> Questions </p> <br>


  <input type="submit" name="submit" value="Submit">
  </form>

</body>

<script>
  console.log(d3); // test if d3 is loaded

  var width = 600;
  var height = 1200;

  // PART 1 //
  var svg1 = d3
    .select("svg1")
    .attr("viewBox", [0, 0, width, height])
    .property("value", []);

  // margins
  var width = 450
      height = 450
      margin = 40

  // The radius = width/2 or height/2 (smallest one)
  var radius = Math.min(width, height) / 2 - margin

  // append svg to div
  var svg = d3.select("#pie_chart")
    .append("svg")
      .attr("width", width)
      .attr("height", height)
    .append("g")
      .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

  // Data Averages
  var data = {Total:13.56363636, Female:9.531818182, Male:17.6}

  // Colors
  var color = d3.scaleOrdinal()
    .domain(data)
    .range(["#af7aa1","#edc949","#e15759"])

  // Individual group position
  var pie = d3.pie()
    .value(function(d) {return d.value; })
  var data_ready = pie(d3.entries(data))

  var arcGenerator = d3.arc()
    .innerRadius(0)
    .outerRadius(radius)

  // Create chart: path using arc.
  svg
    .selectAll('slices')
    .data(data_ready)
    .enter()
    .append('path')
    .attr('d', d3.arc()
      .innerRadius(0)
      .outerRadius(radius)
    )
    .attr('fill', function(d){ return(color(d.data.key)) })
    .attr("stroke", "white")
    .style("stroke-width", "4px")
    .style("opacity", 0.5)

    // Annotate Slices
  svg
    .selectAll('slices')
    .data(data_ready)
    .enter()
    .append('text')
    .text(function(d){ return d.data.key})
    .attr("transform", function(d) { return "translate(" + arcGenerator.centroid(d) + ")";  })
    .style("text-anchor", "middle")
    .style("font-size", 25)

  // PART 2 //

  var svg2 = d3.select("#svg2")
  margin = 200
  width = svg2.attr("width") - margin
  height = svg2.attr("height") - margin

  var xScale = d3.scaleBand().range([0, width]).padding(0.4);
  var yScale = d3.scaleLinear().range([height, 0]);

  var g = svg2.append("g").attr("transform", "translate(" + 100 + "," + 100 + ")");

  d3.csv("age-deaths.csv", function(e, data) {
    if (e) { throw e; }

    xScale.domain(data.map(function(d) { return d.age_group; }));
    yScale.domain([0, 17000]);

    g.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(xScale))
      .append("text")
      .attr("y", height - 260)
      .attr("x", width)
      .attr("text-anchor", "end")
      .attr("stroke", "black")
      .text("Age group");

    g.append("g")
      .call(d3.axisLeft(yScale).tickFormat(function(d){
          return d;
      }).ticks(10))
      .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", "-5.1em")
      .attr("text-anchor", "end")
      .attr("stroke", "black")
      .text("Number of deaths");

    g.selectAll(".bar")
      .data(data)
      .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return xScale(d.age_group); })
      .attr("y", function(d) { return yScale(d.number); })
      .attr("width", xScale.bandwidth())
      .attr("height", function(d) { return height - yScale(d.number); });

    svg2.append("text")
      .attr("transform", "translate(100,0)")
      .attr("x", 50)
      .attr("y", 50)
      .attr("font-size", "24px")
      .text("Number of overdose deaths by age group")
  });



  // PART 3 //

  let articleData = 
    [ {
        "Drugs": "Any Opiod",
        "Year": 1999,
        "Number": 8050,
        "Deathsper100000": 2.9
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2000,
        "Number": 8407,
        "Deathsper100000": 3
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2002,
        "Number": 11920,
        "Deathsper100000": 4.1
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2003,
        "Number": 12940,
        "Deathsper100000": 4.5
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2004,
        "Number": 13756,
        "Deathsper100000": 4.7
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2005,
        "Number": 14918,
        "Deathsper100000": 5.1
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2006,
        "Number": 17545,
        "Deathsper100000": 5.9
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2007,
        "Number": 18516,
        "Deathsper100000": 6.1
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2008,
        "Number": 19582,
        "Deathsper100000": 6.4
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2009,
        "Number": 20422,
        "Deathsper100000": 6.6
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2010,
        "Number": 21089,
        "Deathsper100000": 6.8
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2011,
        "Number": 22784,
        "Deathsper100000": 7.3
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2012,
        "Number": 23166,
        "Deathsper100000": 7.4
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2013,
        "Number": 25052,
        "Deathsper100000": 7.9
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2014,
        "Number": 28647,
        "Deathsper100000": 9
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2015,
        "Number": 33091,
        "Deathsper100000": 10.4
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2016,
        "Number": 42249,
        "Deathsper100000": 13.3
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2017,
        "Number": 47600,
        "Deathsper100000": 14.9
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2018,
        "Number": 46802,
        "Deathsper100000": 14.6
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2019,
        "Number": 49860,
        "Deathsper100000": 15.5
        },
        {
        "Drugs": "Any Opiod",
        "Year": 2020,
        "Number": 68630,
        "Deathsper100000": 21.4
        },
        {
        "Drugs": "Heroin",
        "Year": 1999,
        "Number": 1960,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2000,
        "Number": 1842,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2002,
        "Number": 2089,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2003,
        "Number": 2080,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2004,
        "Number": 1878,
        "Deathsper100000": 0.6
        },
        {
        "Drugs": "Heroin",
        "Year": 2005,
        "Number": 2009,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2006,
        "Number": 2088,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2007,
        "Number": 2399,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Heroin",
        "Year": 2008,
        "Number": 3041,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Heroin",
        "Year": 2009,
        "Number": 3278,
        "Deathsper100000": 1.1
        },
        {
        "Drugs": "Heroin",
        "Year": 2010,
        "Number": 3036,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Heroin",
        "Year": 2011,
        "Number": 4397,
        "Deathsper100000": 1.4
        },
        {
        "Drugs": "Heroin",
        "Year": 2012,
        "Number": 5925,
        "Deathsper100000": 1.9
        },
        {
        "Drugs": "Heroin",
        "Year": 2013,
        "Number": 8257,
        "Deathsper100000": 2.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2014,
        "Number": 10574,
        "Deathsper100000": 3.4
        },
        {
        "Drugs": "Heroin",
        "Year": 2015,
        "Number": 12989,
        "Deathsper100000": 4.1
        },
        {
        "Drugs": "Heroin",
        "Year": 2016,
        "Number": 15469,
        "Deathsper100000": 4.9
        },
        {
        "Drugs": "Heroin",
        "Year": 2017,
        "Number": 15482,
        "Deathsper100000": 4.9
        },
        {
        "Drugs": "Heroin",
        "Year": 2018,
        "Number": 14996,
        "Deathsper100000": 4.7
        },
        {
        "Drugs": "Heroin",
        "Year": 2019,
        "Number": 14019,
        "Deathsper100000": 4.4
        },
        {
        "Drugs": "Heroin",
        "Year": 2020,
        "Number": 13165,
        "Deathsper100000": 4.1
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 1999,
        "Number": 2749,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2000,
        "Number": 2917,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2002,
        "Number": 4416,
        "Deathsper100000": 1.5
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2003,
        "Number": 4867,
        "Deathsper100000": 1.7
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2004,
        "Number": 5231,
        "Deathsper100000": 1.8
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2005,
        "Number": 5774,
        "Deathsper100000": 1.9
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2006,
        "Number": 7017,
        "Deathsper100000": 2.3
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2007,
        "Number": 8158,
        "Deathsper100000": 2.7
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2008,
        "Number": 9119,
        "Deathsper100000": 3
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2009,
        "Number": 9735,
        "Deathsper100000": 3.1
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2010,
        "Number": 10943,
        "Deathsper100000": 3.5
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2011,
        "Number": 11693,
        "Deathsper100000": 3.7
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2012,
        "Number": 11140,
        "Deathsper100000": 3.5
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2013,
        "Number": 11346,
        "Deathsper100000": 3.5
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2014,
        "Number": 12159,
        "Deathsper100000": 3.8
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2015,
        "Number": 12727,
        "Deathsper100000": 3.9
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2016,
        "Number": 14487,
        "Deathsper100000": 4.4
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2017,
        "Number": 14495,
        "Deathsper100000": 4.4
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2018,
        "Number": 12552,
        "Deathsper100000": 3.8
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2019,
        "Number": 11886,
        "Deathsper100000": 3.6
        },
        {
        "Drugs": "Natural and semisynthetic opiods",
        "Year": 2020,
        "Number": 13471,
        "Deathsper100000": 4
        },
        {
        "Drugs": "Methadone",
        "Year": 1999,
        "Number": 784,
        "Deathsper100000": 0.3
        },
        {
        "Drugs": "Methadone",
        "Year": 2000,
        "Number": 986,
        "Deathsper100000": 0.4
        },
        {
        "Drugs": "Methadone",
        "Year": 2002,
        "Number": 2358,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Methadone",
        "Year": 2003,
        "Number": 2972,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Methadone",
        "Year": 2004,
        "Number": 3845,
        "Deathsper100000": 1.3
        },
        {
        "Drugs": "Methadone",
        "Year": 2005,
        "Number": 4460,
        "Deathsper100000": 1.5
        },
        {
        "Drugs": "Methadone",
        "Year": 2006,
        "Number": 5406,
        "Deathsper100000": 1.8
        },
        {
        "Drugs": "Methadone",
        "Year": 2007,
        "Number": 5518,
        "Deathsper100000": 1.8
        },
        {
        "Drugs": "Methadone",
        "Year": 2008,
        "Number": 4924,
        "Deathsper100000": 1.6
        },
        {
        "Drugs": "Methadone",
        "Year": 2009,
        "Number": 4696,
        "Deathsper100000": 1.5
        },
        {
        "Drugs": "Methadone",
        "Year": 2010,
        "Number": 4577,
        "Deathsper100000": 1.5
        },
        {
        "Drugs": "Methadone",
        "Year": 2011,
        "Number": 4418,
        "Deathsper100000": 1.4
        },
        {
        "Drugs": "Methadone",
        "Year": 2012,
        "Number": 3932,
        "Deathsper100000": 1.2
        },
        {
        "Drugs": "Methadone",
        "Year": 2013,
        "Number": 3591,
        "Deathsper100000": 1.1
        },
        {
        "Drugs": "Methadone",
        "Year": 2014,
        "Number": 3400,
        "Deathsper100000": 1.1
        },
        {
        "Drugs": "Methadone",
        "Year": 2015,
        "Number": 3301,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Methadone",
        "Year": 2016,
        "Number": 3373,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Methadone",
        "Year": 2017,
        "Number": 3194,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Methadone",
        "Year": 2018,
        "Number": 3023,
        "Deathsper100000": 0.9
        },
        {
        "Drugs": "Methadone",
        "Year": 2019,
        "Number": 2740,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Methadone",
        "Year": 2020,
        "Number": 3543,
        "Deathsper100000": 1.1
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 1999,
        "Number": 730,
        "Deathsper100000": 0.3
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2000,
        "Number": 782,
        "Deathsper100000": 0.3
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2002,
        "Number": 1295,
        "Deathsper100000": 0.4
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2003,
        "Number": 1400,
        "Deathsper100000": 0.5
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2004,
        "Number": 1664,
        "Deathsper100000": 0.6
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2005,
        "Number": 1742,
        "Deathsper100000": 0.6
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2006,
        "Number": 2707,
        "Deathsper100000": 0.9
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2007,
        "Number": 2213,
        "Deathsper100000": 0.7
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2008,
        "Number": 2306,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2009,
        "Number": 2946,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2010,
        "Number": 3007,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2011,
        "Number": 2666,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2012,
        "Number": 2628,
        "Deathsper100000": 0.8
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2013,
        "Number": 3105,
        "Deathsper100000": 1
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2014,
        "Number": 5544,
        "Deathsper100000": 1.8
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2015,
        "Number": 9580,
        "Deathsper100000": 3.1
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2016,
        "Number": 19413,
        "Deathsper100000": 6.2
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2017,
        "Number": 28466,
        "Deathsper100000": 9
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2018,
        "Number": 31335,
        "Deathsper100000": 9.9
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2019,
        "Number": 36359,
        "Deathsper100000": 11.4
        },
        {
        "Drugs": "Synthetic opiods other than mathadone",
        "Year": 2020,
        "Number": 56516,
        "Deathsper100000": 17.8
        }
    ]

    let svg3 = d3.select(".plotSvg")
    .style("overflow","visible") // some tooltips stray outside the SVG border
    .append("g")
    .attr("transform", "translate(50,50)")

    let xScaleScatter = d3.scaleLinear()
    .domain([1999, 2022])   // my x-variable has a max of 2500
    .range([0, 600]);   // my x-axis is 600px wide

    let yScaleScatter = d3.scaleLinear()
    .domain([0, 100])   // my y-variable has a max of 1200
    .range([400, 0]);   // my y-axis is 400px high
                        // (the max and min are reversed because the 
                        // SVG y-value is measured from the top)

    let yVar = "deathsper";


    let pubColors = {
        "Any Opiod": "#d62728",
        "Heroin": "#2ca02c",
        "Natural and semisynthetic opiods": "#7f7f7f",
        "Synthetic opiods other than mathadone": "#1f77b4",
        "Methadone": "#ff7f0e"
    }

    svg3.append("g")       // the axis will be contained in an SVG group element
    .attr("id","yAxis")
    .call(d3.axisLeft(yScaleScatter)
            .ticks(5)
            .tickFormat(d3.format("d"))
            .tickSizeOuter(0)
        )
        
    svg3.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(+160,' + 50 + ')rotate(-360)')
        .style('font-family', 'Helvetica')
        .style('font-size', 12)
        .text('Any Opiod');
    svg3.append('line')
        .attr('x1', 10)
        .attr('transform', 'translate(+40,' + 45 + ')rotate(-360)')
        .attr('stroke', "#d62728")
        .style('stroke-width', 7);    

    svg3.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(+160,' + 65 + ')rotate(-360)')
        .style('font-family', 'Helvetica')
        .style('width', 10)
        .style('font-size', 12)
        .text('Natural and semisynthetic opiods');
    svg3.append('line')
        .attr('x1', 10)
        .attr('transform', 'translate(+40,' + 60 + ')rotate(-360)')
        .attr('stroke', "#7f7f7f")
        .style('stroke-width', 7);  

    svg3.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(+160,' + 80 + ')rotate(-360)')
        .style('font-family', 'Helvetica')
        .style('width', 10)
        .style('font-size', 12)
        .text('Heroin');
    svg3.append('line')
        .attr('x1', 10)
        .attr('transform', 'translate(+40,' + 75 + ')rotate(-360)')
        .attr('stroke', "#2ca02c")
        .style('stroke-width', 7);

    svg3.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(+160,' + 95 + ')rotate(-360)')
        .style('font-family', 'Helvetica')
        .style('width', 10)
        .style('font-size', 12)
        .text('Synthetic opiods other than mathadone');
    svg3.append('line')
        .attr('x1', 10)
        .attr('transform', 'translate(+40,' + 90 + ')rotate(-360)')
        .attr('stroke', "#1f77b4")
        .style('stroke-width', 7);

    svg3.append('text')
        .attr('text-anchor', 'middle')
        .attr('transform', 'translate(+160,' + 110 + ')rotate(-360)')
        .style('font-family', 'Helvetica')
        .style('width', 10)
        .style('font-size', 12)
        .text('Methadone');
    svg3.append('line')
        .attr('x1', 10)
        .attr('transform', 'translate(+40,' + 105 + ')rotate(-360)')
        .attr('stroke', "#ff7f0e")
        .style('stroke-width', 7);
    
    svg3.append("g")       
    .attr("transform", "translate(0,400)")    // translate x-axis to bottom of chart
    .attr("id","xAxis")
    .call(d3.axisBottom(xScaleScatter)
            .ticks(5)
            .tickFormat(d3.format("d"))
            .tickSizeOuter(0)
        )

    svg3.selectAll(".bubble")
    .data(articleData)    // bind each element of the data array to one SVG circle
    .join("circle")
    .attr("class", "bubble")
    .attr("cx", d => xScaleScatter(d.Year))   // set the x position based on the number of claps
    .attr("cy", d => yScaleScatter(d.Deathsper100000))   // set the y position based on the number of views
    .attr("r", d => Math.sqrt(d.Deathsper100000)*5)  // set the radius based on the article reading time
    .attr("stroke", d => pubColors[d.Drugs])
    .attr("fill", d => pubColors[d.Drugs])
    .attr("fill-opacity", 0.5)
    .on("mouseover",(e,d) => {    // event listener to show tooltip on hover
        d3.select("#bubble-tip-"+d.Drugs)  // i'm using the publish time as a unique ID
        .style("display","block");
    })
    .on("mouseout", (e,d) => {    // event listener to hide tooltip after hover
        if(!d.toolTipVisible){
        d3.select("#bubble-tip-"+d.Drugs)
            .style("display","none");
        }
    })
    .on("click", (e,d) => {    // event listener to make tooltip remain visible on click
        if(!d.toolTipVisible){
        d3.select("#bubble-tip-"+d.Drugs)
            .style("display", "block");
        d.toolTipVisible = true;
        }
        else{
        d3.select("#bubble-tip-"+d.Drugs)
            .style("display", "none");
        d.toolTipVisible = false;
        }
    });

    svg3.selectAll(".bubble-tip")
    .data(articleData)
    .join("g")
    .attr("class", "bubble-tip")
    .attr("id", (d)=> "bubble-tip-"+d.Drugs)
    .attr("transform", d => "translate(" + (xScaleScatter( d.Year )+20) + ", " + yScaleScatter( d.Deathsper100000) + ")"  )
    .style("display", "none")   
    .append("rect")     // this is the background to the tooltip
    .attr("x",-5)
    .attr("y",-20)
    .attr("rx",5)
    .attr("fill","white")
    .attr("fill-opacity", 0.9)
    .attr("width",180)
    .attr("height",100)

    // SVG does not wrap text
    // so I add a new text element for each line (4 words)
    svg3.selectAll(".bubble-tip")
    .join("text")
    .text(d =>d.Drugs.split(" ").slice(0,4).join(" "))
    .style("font-family", "sans-serif")
    .style("font-size", 14)
    .attr("stroke", "none")
    .attr("fill", d => pubColors[d.Drugs])

    svg3.selectAll(".bubble-tip")
    .append("text")
    .text(d =>d.Drugs.split(" ").slice(4,8).join(" "))
    .attr("y", 18)
    .style("font-family", "sans-serif")
    .style("font-size", 14)
    .attr("stroke", "none")
    .attr("fill", d => pubColors[d.Drugs])

    svg3.selectAll(".bubble-tip")
    .append("text")
    .text(d =>d.Drugs.split(" ").slice(8,12).join(" ") + (d.Drugs.split(" ").length > 12 ? "..." : "") )
    .attr("y", 36)
    .style("font-family", "sans-serif")
    .style("font-size", 14)
    .attr("stroke", "none")
    .attr("fill", d => pubColors[d.Drugs])

    svg3.selectAll(".bubble-tip")
    .append("text")
    .attr("y", d => (d.Drugs.split(" ").length > 8 ? 54 : 36) )
    .style("font-family", "sans-serif")
    .style("font-style", "italic")
    .style("font-size", 14)
    .attr("stroke", "none")
    .attr("fill", d => pubColors[d.Drugs])

    svg3.selectAll(".bubble-tip")
    .append("text")
    .classed("bubble-tip-yText", true)
    .text(d => "(" + d[yVar] + " " + yVar + ")")
    .attr("y", d => (d.Drugs.split(" ").length > 8 ? 72 : 54) )
    .style("font-family", "sans-serif")
    .style("font-size", 14)
    .attr("stroke", "none")
    .attr("fill", d => pubColors[d.Drugs])

    let xVar = document.getElementById("select-x-var").value;

    document.getElementById("select-x-var").addEventListener("change", (e)=>{
    
    // update the x-variable based on the user selection
    xVar = e.target.value   
    
    // transition each circle element
        svg3.selectAll(".bubble")
        .transition()
        .duration(1000)
        .attr("cx", (d) => xScaleScatter(d[xVar]) )
    
    // transition each tooltip
        svg3.selectAll(".bubble-tip")
        .transition()
        .duration(1000)
        .attr("transform", d => "translate(" + (xScaleScatter(d[xVar])+20) + ", " +  yScaleScatter(d[yVar]) + ")" )
    })

    document.getElementById("select-y-var").addEventListener("change", (e)=>{
    
    // update the x-variable based on the user selection
    yVar = e.target.value   

    // rescale the x-axis
    yScaleScatter = d3.scaleLinear()
        .domain([0, d3.max(articleData, d => d[yVar]) ])    
        .range([400, 0]);

    // redraw the x-axis
    svg3.select("#yAxis")            
        .call(d3.axisLeft(yScaleScatter)
            .ticks(5)
            .tickFormat(d3.format("d"))
            .tickSizeOuter(0)
        )

    // transition each circle element and tooltip
    svg3.selectAll(".bubble")
        .transition()
        .duration(1000)
        .attr("cy", (d) => yScaleScatter(d[yVar]) )
        
    svg3.selectAll(".bubble-tip-yText")
        .text(d => "(" + d[yVar] + " " + yVar + ")")
    
    svg3.selectAll(".bubble-tip")
        .attr("transform", d => "translate(" + (xScaleScatter(d[xVar])+20) + ", " +  yScaleScatter(d[yVar]) + ")" )
    })
</script>

<?php

$fp = fopen('data.txt', 'a');
fwrite($fp, "\n");
fclose($fp);

if(isset($_POST['yes_no1']))
{
  $data = $_POST['yes_no1'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

if(isset($_POST['explain1']))
{
  $data = $_POST['explain1'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

if(isset($_POST['yes_no2']))
{
  $data = $_POST['yes_no2'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

if(isset($_POST['explain2']))
{
  $data = $_POST['explain2'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

if(isset($_POST['yes_no3']))
{
  $data = $_POST['yes_no3'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

if(isset($_POST['explain3']))
{
  $data = $_POST['explain3'];
  $fp = fopen('data.txt', 'a');
  fwrite($fp, $data);
  fwrite($fp, " ");
  fclose($fp);
}

?>

</html>
