<?
/*
Plugin Name: Charts
Plugin URI: https://github.com/danielcarpio/Visualizacion-de-graficas-en-la-Ingenieria-del-Software
Description: Añade gráficas a tu página
Version: 1.0
Author: Daniel Carpio
Author URI: https://github.com/danielcarpio/
License: GNU General Public License v3.0
*/
function add_script(){
    echo '<script src="https://d3js.org/d3.v5.min.js"></script>
    <script type="text/javascript">
    function make_x_gridlines(xRange) {
        return d3.axisBottom(xRange)
            .ticks(8)
    }
    
    function make_y_gridlines(yRange) {
        return d3.axisLeft(yRange)
            .ticks(8)
    }
    
    function horizontalBarGraph(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        var margin = 10;
        var longest_label = "";
    
        for(item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
    
            values.push(item.value);
            
            if(item.color != null){
                colours.push(item.color);
            }else if(item.highlight == true && item.highlight != null){
                colours.push("rgb(234, 6, 0)");
            }else{
                colours.push("rgb(0,40,158)");
            }
        }
    
        var heightBar = 0;
        if(title != null && subtitle != null)
            heightBar = (height*0.8) / (2*values.length-0.5);
        else if(title == null && subtitle == null)
            heightBar = height / (2*values.length);
        else heightBar = 0.85*height / (2*values.length);
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var labelWidth = 0;
        var sizeOfLabels = [];
    
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLabels.push(this.getBBox().width);
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "middle")
            .attr("x", function(d,i){
                return labelWidth - sizeOfLabels[i]
            })
            .attr("y", function(d,i){
                if(title != null && subtitle != null)
                    return height*0.15+((heightBar*2)*i)+heightBar/2;
                else if(title == null && subtitle == null)
                    return height*0.05+heightBar*2*i+heightBar/2;
                else return height*0.1+((heightBar*2)*i)+heightBar/2;
            })
            .text(function(d){return d});
    
        var xRange = d3.scaleLinear()
            .range([0, width-labelWidth-margin*2])
            .domain([0, d3.max(values)]);
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.84;
        else if(title == null && subtitle == null)
            tickSize = -height*0.94;
        else tickSize = -height*0.89;
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform", function(){
                return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
            })
            .call(make_x_gridlines(xRange)
              .tickSize(tickSize)
              .tickFormat("")
           );
           
        var rec = svg.selectAll("rect")
           .data(values)
           .enter()
           .append("rect")
           .attr("stroke-width", "0")
           .attr("fill", function(d,i){
               return colours[i]
           })
           .attr("x", labelWidth + margin)
           .attr("y", function(d,i){
                if(title != null && subtitle != null)
                   return height*0.15+(heightBar*2*i);
                else if(title == null && subtitle == null)
                    return height*0.05+(heightBar*2*i);
                else
                    return height*0.1+(heightBar*2*i);
           })
           .attr("height", heightBar)
           .attr("width", function(d){
               return xRange(d);
           });
    
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform","translate("+ (labelWidth+margin) +"," + height*0.95 + ")")
            .call(axisX);
    
    }
    
    function horizontalBarGraphNoSpace(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        var margin = 10;
        var longest_label = "";
    
        for(item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
    
            values.push(item.value);
            
            if(item.color != null){
                colours.push(item.color);
            }else if(item.highlight == true && item.highlight != null){
                colours.push("rgb(234, 6, 0)");
            }else{
                colours.push("rgb(0,40,158)");
            }
        }
    
        var heightBar = 0;
        if(title != null && subtitle != null)
            heightBar = (height*0.8) / (values.length);
        else if(title == null && subtitle == null)
            heightBar = height*0.9 / (values.length);
        else heightBar = 0.85*height / (values.length);
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var labelWidth = 0;
        var sizeOfLabels = []
    
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLabels.push(this.getBBox().width);
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "middle")
            .attr("x", function(d,i){
                return labelWidth - sizeOfLabels[i]
            })
            .attr("y", function(d,i){
                if(title != null && subtitle != null)
                    return height*0.15+((heightBar)*i)+heightBar/2;
                else if(title == null && subtitle == null)
                    return height*0.05+heightBar*i+heightBar/2;
                else return height*0.1+((heightBar)*i)+heightBar/2;
            })
            .text(function(d){return d});
    
        var xRange = d3.scaleLinear()
            .range([0, width-labelWidth-margin*2])
            .domain([0, d3.max(values)]);
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.84
        else if(title == null && subtitle == null)
            tickSize = -height*0.94
        else tickSize = -height*0.89
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
                else if(title == null && subtitle == null)
                    return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
                else return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
            })
            .call(make_x_gridlines(xRange)
              .tickSize(tickSize)
              .tickFormat("")
           )
           
        var rec = svg.selectAll("rect")
           .data(values)
           .enter()
           .append("rect")
           .attr("stroke", "black")
           .attr("stroke-width", "1")
           .attr("fill", function(d,i){
               return colours[i]
           })
           .attr("x", labelWidth + margin)
           .attr("y", function(d,i){
                if(title != null && subtitle != null)
                   return height*0.15+(heightBar*i);
                else if(title == null && subtitle == null)
                    return height*0.05+(heightBar*i);
                else
                    return height*0.1+(heightBar*i);
           })
           .attr("height", heightBar)
           .attr("width", function(d){
               return xRange(d);
           });
    
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform","translate("+ (labelWidth+margin) +"," + height*0.95 + ")")
            .call(axisX);
    
    }
    
    function verticalBarGraph(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc)
        } else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        
        for (item of obj.data){
            labels.push(item.label);
            values.push(item.value);
            
            if(item.color != null) colours.push(item.color)
            else if(item.highlight == true && item.highlight != null)
                colours.push("rgb(234, 6, 0)");
            else{
                colours.push("rgb(0,40,158)")
            }
        }
    
        var widthBar = (width*0.9) / (2*values.length);
        var yRange = null;
        
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, d3.max(values)]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, d3.max(values)]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, d3.max(values)]);
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";            
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
              .tickSize(-width*0.9)
              .tickFormat("")
            )
    
        var rec = svg.selectAll("rect")
                .data(values)
                .enter()
                .append("rect")
                .attr("stroke-width", "0")
                .attr("fill", function(d,i){
                    return colours[i]
                })
                .attr("x", function(d,i){
                    return widthBar/2 + width*0.1 + (widthBar*2*i);
                })
                .attr("y", function(d){
                    if(title != null && subtitle != null)
                        return height*0.15+yRange(d);
                    else if(title == null && subtitle == null)
                        return height*0.05+yRange(d);
                    else return height*0.1+yRange(d);
                })
                .attr("height", function(d){
                    return yRange(0)-yRange(d);
                })
                .attr("width", widthBar);
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .call(axisY);
    
        if(title != null)
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
    
        if (subtitle != null){
            if(title != null)
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            else
                svg.append("text").text(subtitle)
                .attr("x", width/2)
                .attr("y", height*0.05)
                .attr("text-anchor", "middle");
        }
    
        svg.selectAll("p")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "middle")
            .attr("x", function(d,i){
                return widthBar/2 + width*0.1 + ((widthBar*2)*i) + widthBar/2;
            })
            .attr("y", height*0.95)
            .text(function(d){return d}); 
    }
    
    function verticalBarGraphNoSpace(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc)
        } else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        
        for (item of obj.data){
            labels.push(item.label);
            values.push(item.value);
            
            if(item.color != null) colours.push(item.color)
            else if(item.highlight == true && item.highlight != null)
                colours.push("rgb(234, 6, 0)");
            else{
                colours.push("rgb(0,40,158)")
            }
        }
    
        var widthBar = (width*0.9) / (values.length);
        var yRange = null;
        
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, d3.max(values)]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, d3.max(values)]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, d3.max(values)]);
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";            
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
              .tickSize(-width*0.9)
              .tickFormat("")
            )
    
        var rec = svg.selectAll("rect")
                .data(values)
                .enter()
                .append("rect")
                .attr("stroke-width", "0")
                .attr("fill", function(d,i){
                    return colours[i]
                })
                .attr("x", function(d,i){
                    return width*0.1 + (widthBar*i);
                })
                .attr("y", function(d){
                    if(title != null && subtitle != null)
                        return height*0.15+yRange(d);
                    else if(title == null && subtitle == null)
                        return height*0.05+yRange(d);
                    else return height*0.1+yRange(d);
                })
                .attr("height", function(d){
                    return yRange(0)-yRange(d);
                })
                .attr("width", widthBar);
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .call(axisY);
    
        if(title != null)
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
    
        if (subtitle != null){
            if(title != null)
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            else
                svg.append("text").text(subtitle)
                .attr("x", width/2)
                .attr("y", height*0.05)
                .attr("text-anchor", "middle");
        }
    
        svg.selectAll("p")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "middle")
            .attr("x", function(d,i){
                return widthBar/2 + width*0.1 + (widthBar*i);
            })
            .attr("y", height*0.95)
            .text(function(d){return d}); 
    }
    
    function dotChart(id, width, height, data){
        var obj = JSON.parse(data)
        var title = obj.title;
        var subtitle = obj.subtitle;
        var data = obj.data;
        if(data.length > 4){
            data = data.slice(0, 4);
        }
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc)
        } else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var col = ["#FFBA08","#F40000","#3F88C5","#3D348B"];
        var xMax = 0;
        var yMax = 0;
        var labels = [];
        var colours = [];
        for(i = 0; i < data.length; i++){
            d = data[i];
            labels.push(d.label);
            for(v of d.values){
                if(v.x > xMax) xMax = v.x;
                if(v.y > yMax) yMax = v.y;
            }
            if(d.color != null) colours.push(d.color);
            else colours.push(col[i]);
        }
        
        var labelWidth = 0;
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
        
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "hanging")
            .attr("x", function(d,i){
                return width*0.99-labelWidth;
            })
            .attr("y", function(d,i){
                return height*0.2*(i+1);
            });
        
        svg.selectAll("rectangulo")
            .data(colours)
            .enter()
            .append("rect")
            .style("fill", function(d){return d})
            .attr("height", width*0.03)
            .attr("width", width*0.03)
            .attr("x", function(d,i){return width*0.95-labelWidth})
            .attr("y", function(d,i){return height*0.19575*(i+1)})
            .style("stroke-width", 0)
            .style("stroke", 0);
        
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var xRange = d3.scaleLinear()
            .range([0, width*0.8-labelWidth])
            .domain([0, xMax]);
        
        var yRange = null;
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear()
                .range([height*0.8, 0])
                .domain([0, yMax]);
        else if(title == null && subtitle == null)
            yRange = d3.scaleLinear()
                .range([height*0.85, 0])
                .domain([0, yMax]);
        else yRange = d3.scaleLinear()
                .range([height*0.83, 0])
                .domain([0, yMax]);
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+(width*0.05)+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+(width*0.05)+","+height*0.1+")";
                else
                    return "translate("+(width*0.05)+","+height*0.12+")";
            })
            .call(make_y_gridlines(yRange)
              .tickSize(-width*0.8 + labelWidth)
              .tickFormat("")
            )
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.85
        else if(title == null && subtitle == null)
            tickSize = -height*0.9
        else tickSize = -height*0.88
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform","translate("+(width*0.05)+","+height*0.95+")")
            .call(make_x_gridlines(xRange)
              .tickSize(tickSize)
              .tickFormat("")
            )    
        
        for(let index = data.length-1; index >= 0; index--){
            var d = data[index];
            for(v of d.values){
                switch(index){
                    case 0:
                        svg.append("circle")
                            .attr("cx", width*0.05+xRange(v.x))
                            .attr("cy", function(){
                                if(title != null && subtitle != null){
                                    return height*0.15 + yRange(v.y)
                                } else if(title == null && subtitle == null){
                                    return height*0.1 + yRange(v.y)
                                } else {
                                    return height*0.12 + yRange(v.y)
                                }
                            })
                            .attr("r", Math.min(height, width)*0.01)
                            .attr("fill", colours[index])
                            .attr("stroke", colours[index])
                            .attr("stroke-width", "2");
                    break;
                    case 1:
                        svg.append("text")
                            .text("x")
                            .attr("text-anchor", "middle")
                            .attr("alignment-baseline", "middle")
                            .attr("x", width*0.05+xRange(v.x))
                            .attr("y", function(){
                                if(title != null && subtitle != null){
                                    return height*0.15 + yRange(v.y)
                                } else if(title == null && subtitle == null){
                                    return height*0.1 + yRange(v.y)
                                } else {
                                    return height*0.12 + yRange(v.y)
                                }
                            })
                            .attr("font-size", Math.min(height, width)*0.05)
                            .attr("font-family", "sans-serif")
                            .attr("fill", colours[index])
                            .attr("font-weight", "bold");
                    break;
                    case 2:
                        svg.append("text")
                            .text("x")
                            .attr("text-anchor", "middle")
                            .attr("alignment-baseline", "middle")
                            .attr("x", width*0.05 + xRange(v.x))
                            .attr("y", function(){
                                if(title != null && subtitle != null){
                                    return height*0.15 + yRange(v.y)
                                } else if(title == null && subtitle == null){
                                    return height*0.1 + yRange(v.y)
                                } else {
                                    return height*0.12 + yRange(v.y)
                                }
                            })
                            .attr("font-size", Math.min(height, width)*0.05)
                            .attr("font-family", "sans-serif")
                            .attr("font-weight", "bold")
                            .attr("fill", colours[index]);
                    break;
                    case 3:
                        svg.append("text")
                        .text("■")
                        .attr("text-anchor", "middle")
                        .attr("alignment-baseline", "middle")
                        .attr("x", width*0.05 + xRange(v.x))
                        .attr("y", function(){
                            if(title != null && subtitle != null){
                                return height*0.15 + yRange(v.y)
                            } else if(title == null && subtitle == null){
                                return height*0.1 + yRange(v.y)
                            } else {
                                return height*0.12 + yRange(v.y)
                            }
                        })
                        .attr("font-size", Math.min(height, width)*0.05)
                        .attr("font-family", "sans-serif")
                        .attr("fill", colours[index]);
                    break;
                }
            }
        }
        
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform", "translate("+width*0.05+"," + height*0.95 + ")")
            .call(axisX);
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.05+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.05+"," + height*0.1 + ")";
                else
                    return "translate("+width*0.05+"," + height*0.12 + ")";
            }).call(axisY);
    
    }
    
    function lineChart(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        if(obj.data.length > 4){
            obj.data = obj.data.slice(0,4);
        }
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var baseColours = ["#FFBA08","#F40000","#3F88C5","#3D348B"];
        var stroke_dash = ["", "20,5", "20,10", "10,5"];
    
        var values = [];
        var labels = [];
        var colors = [];
        var valuesLength = 0;
        var yMax = 0;
        for(const item of obj.data){
            labels.push(item.label);
            values.push(item.values);
    
            if (yMax<Math.max.apply(null, item.values)){
                yMax = Math.max.apply(null, item.values);
            }
    
            valuesLength += values.length;
            if(item.color != null){
                colors.push(item.color);
            }else{
                colors.push(baseColours.pop());
            }
        }
    
    
        var yRange = null;
    
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, yMax]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, yMax]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, yMax]);
        
    
    
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
              .tickSize(-width*0.6)
              .tickFormat("")
        );
    
    
        var dat = obj.data;
        var emptySpace = width*0.57/(obj.labels.length-1);
        for (let index = 0; index < dat.length; index++) {
            var d = dat[index];
            for (let x = 0; x < d.values.length-1; x++) {
                svg.append("line")
                    .style("stroke", colors[index])
                    .attr("x1", width*0.11 + emptySpace*(x))
                    .attr("y1", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + yRange(d.values[x])
                        else if(title == null && subtitle == null)
                            return height*0.05 + yRange(d.values[x])
                        else
                            return height*0.1 + yRange(d.values[x])
                    })
                    .attr("x2", width*0.11 + emptySpace*(x+1))
                    .attr("y2", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + yRange(d.values[x+1])
                        else if(title == null && subtitle == null)
                            return height*0.05 + yRange(d.values[x+1])
                        else
                            return height*0.1 + yRange(d.values[x+1])
                    }
                    )
                    .style("stroke-width", 3)
                    .style("stroke-dasharray", stroke_dash[index]);
            }
        }
    
        for (let index = 0; index < obj.labels.length; index++) {
            svg.append("text")
                .text("|")
                .attr("y", height*0.91)
                .attr("x", width*0.11 + emptySpace*(index))
                .attr("font-size", Math.min(width,height)*0.03);
            svg.append("text")
                .text(obj.labels[index])
                .attr("y", height*0.95)
                .attr("x", width*0.11 + emptySpace*(index))
                .attr("text-anchor", "middle")
                .attr("font-size", Math.min(width,height)*0.03);
        }
    
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .style("font-size", Math.min(width,height)*0.03)
            .call(axisY);
    
        svg.append("rect")
            .attr("height", 1)
            .attr("width", width*0.6)
            .attr("x", width*0.1)
            .attr("y", height*0.9);
    
        
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("x", width*0.96)
            .attr("y", function(d, i){
                return height*0.2*(i+1)
            })
            .attr("font-size", Math.min(width, height)*0.025)
            .attr("text-anchor", "end")
            .attr("alignment-baseline", "hanging");
    
        svg.selectAll("rectangulo")
            .data(colors)
            .enter()
            .append("rect")
            .style("fill", function(d){return d})
            .attr("x", width*0.97)
            .attr("y", function(d,i){
                return height*0.2*(i+1)
            })
            .attr("height", Math.min(width, height)*0.03)
            .attr("width", Math.min(width, height)*0.03)
            .style("stroke-width", 0)
            .style("stroke", 0);
    
        if(title != null)
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        
        if (subtitle != null){
            if(title != null)
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            else
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
        }
    
        
    }
    
    function horizontalBoxPlot(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
            
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var colours = [];
        var coloursBorder = [];
        var margin = 10;
        var longest_label = "";
        var maxValue = 0;
    
        for (const item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
    
            if(item.color != null && item.colorBorder != null){
                colours.push(item.color);
                coloursBorder.push(item.colorBorder);
            }else if(item.highlight == true && item.highlight != null){
                colours.push("rgb(200,6,0)");
                coloursBorder.push("rgb(100, 6, 0)");
            }else{
                colours.push("rgb(0,40,158)");
                coloursBorder.push("rgb(0,10,108)");
            }
    
            var i = item.values;
            if(i.max > maxValue) maxValue = i.max;
            if(i.outliers != null){
                for(v of i.outliers){
                    if(v > maxValue) maxValue = v;
                }
            }
        }
        
        var heightBar = 0;
        if(title != null && subtitle != null)
            heightBar = (height*0.8) / (2*obj.data.length-0.5);
        else if(title == null && subtitle == null)
            heightBar = height / (2*obj.data.length);
        else heightBar = 0.85*height / (2*obj.data.length);
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var labelWidth = 0;
        var sizeOfLabels = [];
    
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLabels.push(this.getBBox().width);
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "middle")
            .attr("x", function(d,i){
                return labelWidth - sizeOfLabels[i]
            })
            .attr("y", function(d,i){
                if(title != null && subtitle != null)
                    return height*0.15+((heightBar*2)*i)+heightBar/2;
                else if(title == null && subtitle == null)
                    return height*0.05+heightBar*2*i+heightBar/2;
                else return height*0.1+((heightBar*2)*i)+heightBar/2;
            })
            .text(function(d){return d});
    
        var xRange = d3.scaleLinear()
            .range([0, width-labelWidth-margin*2])
            .domain([0, maxValue]);
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.84;
        else if(title == null && subtitle == null)
            tickSize = -height*0.94;
        else tickSize = -height*0.89;
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform", function(){
                return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
            })
            .call(make_x_gridlines(xRange)
              .tickSize(tickSize)
              .tickFormat("")
            );
        
        for(let index = 0; index < obj.data.length; index++){
            data = obj.data[index];
            labels.push(data.label);
            values = data.values;
            svg.append("line")
                .attr("x1", labelWidth+margin + xRange(values.min))
                .attr("x2", labelWidth+margin + xRange(values.max))
                .attr("y1", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index)+heightBar/2;
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index+heightBar/2;
                    else return height*0.1+((heightBar*2)*index)+heightBar/2;
                })
                .attr("y2", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index)+heightBar/2;
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index+heightBar/2;
                    else return height*0.1+((heightBar*2)*index)+heightBar/2;
                })
                .style("stroke", "black")
                .style("stroke-width", 2);
    
            svg.append("text")
                .text("|")
                .attr("x", labelWidth+margin + xRange(values.min))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index)+heightBar/2;
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index+heightBar/2;
                    else return height*0.1+((heightBar*2)*index)+heightBar/2;
                })
                .attr("font-size", Math.min(width, height)*0.05)
                .attr("text-anchor", "middle")
                .attr("alignment-baseline", "middle");
    
            svg.append("text")
                .text("|")
                .attr("x", labelWidth+margin + xRange(values.max))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index)+heightBar/2;
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index+heightBar/2;
                    else return height*0.1+((heightBar*2)*index)+heightBar/2;
                })
                .attr("font-size", Math.min(width, height)*0.05)
                .attr("text-anchor", "middle")
                .attr("alignment-baseline", "middle");
    
            svg.append("rect")
                .attr("stroke-width", "4")
                .attr("fill", function(){
                    return colours[index];
                })
                .attr("stroke", function(){
                    return coloursBorder[index];
                })
                .attr("x", xRange(values.q1) + labelWidth+margin)
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index);
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index;
                    else return height*0.1+((heightBar*2)*index);
                })
                .attr("height", heightBar)
                .attr("width", xRange(values.q3 - values.q1));
    
            svg.append("rect")
                .attr("stroke-width", "4")
                .attr("fill", function(){
                    return colours[index];
                })
                .attr("stroke", function(){
                    return coloursBorder[index];
                })
                .attr("x", xRange(values.median) + labelWidth+margin)
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return height*0.15+((heightBar*2)*index);
                    else if(title == null && subtitle == null)
                        return height*0.05+heightBar*2*index;
                    else return height*0.1+((heightBar*2)*index);
                })
                .attr("height", heightBar)
                .attr("width", 1);
            
            if(values.outliers == null) continue;
            for(v of values.outliers){
                svg.append("circle")
                    .attr("cx", labelWidth+margin + xRange(v))
                    .attr("cy", function(){
                        if(title != null && subtitle != null)
                            return height*0.15+((heightBar*2)*index)+heightBar/2;
                        else if(title == null && subtitle == null)
                            return height*0.05+heightBar*2*index+heightBar/2;
                        else return height*0.1+((heightBar*2)*index)+heightBar/2;
                    })
                    .attr("r", Math.min(width, height)*0.01)
                    .attr("stroke", "black")
                    .attr("stroke-width", 1)
                    .attr("fill", "none");
            }
        }
    
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform", "translate("+(labelWidth+margin)+", "+height*0.95+")")
            .style("font-size", Math.min(width,height)*0.03)
            .call(axisX);
        
    }
    
    function verticalBoxPlot(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
    
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
            
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc)
        } else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var colours = [];
        var coloursBorder = [];
        var margin = 10;
        var maxValue = 0;
    
        for (const item of obj.data){
            labels.push(item.label);
            if(item.color != null && item.colorBorder != null){
                colours.push(item.color);
                coloursBorder.push(item.colorBorder);
            }else if(item.highlight == true && item.highlight != null){
                colours.push("rgb(200,6,0)");
                coloursBorder.push("rgb(100, 6, 0)");
            }else{
                colours.push("rgb(0,40,158)");
                coloursBorder.push("rgb(0,10,108)");
            }
    
            var i = item.values;
            if(i.max > maxValue) maxValue = i.max;
            if(i.outliers != null){
                for(v of i.outliers){
                    if(v > maxValue) maxValue = v;
                }
            }
        }
    
        var widthBar = (width*0.9) / (2*obj.data.length);
        var yRange = null;
    
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, maxValue]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, maxValue]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, maxValue]);
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";            
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
            .tickSize(-width*0.9)
            .tickFormat("")
        );
    
        if (subtitle != null){
            if(title != null)
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            else
                svg.append("text").text(subtitle)
                .attr("x", width/2)
                .attr("y", height*0.05)
                .attr("text-anchor", "middle");
        }
    
        if(title != null)
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        
        svg.selectAll("p")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "middle")
            .attr("x", function(d,i){
                return widthBar/2 + width*0.1 + ((widthBar*2)*i) + widthBar/2;
            })
            .attr("y", height*0.95)
            .text(function(d){return d});
    
    
    
        for(let index = 0; index < obj.data.length; index++){
            data = obj.data[index];
            labels.push(data.label);
            values = data.values;
            svg.append("line")
                .attr("x1", widthBar + width*0.1 + ((widthBar*2)*index))
                .attr("x2", widthBar + width*0.1 + ((widthBar*2)*index))
                .attr("y1", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.min));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.min));
                    else
                        return (height*0.10 + yRange(values.min));
                })
                .attr("y2", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.max));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.max));
                    else
                        return (height*0.1 + yRange(values.max));
                })
                .style("stroke", "black")
                .style("stroke-width", 2);
        
            svg.append("text")
                .text("-")
                .attr("x", widthBar + width*0.1 + ((widthBar*2)*index))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.min));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.min));
                    else
                        return (height*0.10 + yRange(values.min));
                })
                .attr("font-size", Math.min(width, height)*0.1)
                .attr("text-anchor", "middle")
                .attr("alignment-baseline", "middle");
        
            svg.append("text")
                .text("-")
                .attr("x", widthBar + width*0.1 + ((widthBar*2)*index))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.max));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.max));
                    else
                        return (height*0.1 + yRange(values.max));
                })
                .attr("font-size", Math.min(width, height)*0.1)
                .attr("text-anchor", "middle")
                .attr("alignment-baseline", "middle");
        
            svg.append("rect")
                .attr("stroke-width", "4")
                .attr("fill", function(){
                    return colours[index];
                })
                .attr("stroke", function(){
                    return coloursBorder[index];
                })
                .attr("x", widthBar/2 + width*0.1 + ((widthBar*2)*index))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.q3));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.q3));
                    else
                        return (height*0.1 + yRange(values.q3));
                })
                .attr("height", yRange(0) - yRange(values.q3 - values.q1))
                .attr("width", widthBar);
        
            svg.append("rect")
                .attr("stroke-width", "4")
                .attr("fill", function(){
                    return colours[index];
                })
                .attr("stroke", function(){
                    return coloursBorder[index];
                })
                .attr("x", widthBar/2 + width*0.1 + ((widthBar*2)*index))
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return (height*0.15 + yRange(values.median));
                    else if(title == null && subtitle == null)
                        return (height*0.05 + yRange(values.median));
                    else
                        return (height*0.1 + yRange(values.median));
                })
                .attr("height", 1)
                .attr("width", widthBar);
        
            if(values.outliers == null) continue;
            for(v of values.outliers){
                svg.append("circle")
                    .attr("cx", widthBar + width*0.1 + ((widthBar*2)*index))
                    .attr("cy", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v));
                        else
                            return (height*0.1 + yRange(v));
                    })
                    .attr("r", Math.min(width, height)*0.01)
                    .attr("stroke", "black")
                    .attr("stroke-width", 1)
                    .attr("fill", "none");
            }
        }
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .call(axisY);
    }
    
    function groupedHorizontalBarChart(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        var coloursHighlight = [];
        var margin = 10;
        var longest_label = "";
        var longest_legend = "";
        var maxValue = 0;
        var numValues = 0;
    
        if(obj.data.length > 4){
            obj.data = obj.data.slice(0, 4);
        }
    
        for(const item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
            values.push(item.values);
            numValues += item.values.length;
            if(Math.max.apply(null, item.values) > maxValue) maxValue = Math.max.apply(null, item.values);
        }
        for(const s of obj.legend){
            if(s.length > longest_legend.length) longest_legend = s;
        }
    
        if(obj.colorHighlight == null){
            coloursHighlight.push(["rgb(63.75,0,0)", "rgb(127.5,0,0)", "rgb(191.25,0,0)", "rgb(255,0,0)"]);
        } else {
            coloursHighlight.push(obj.colorHighlight);
        }
        if(obj.color == null){
            colours.push(["rgb(0,0,63.75)", "rgb(0,0,127.5)", "rgb(0,0,191.25)", "rgb(0,0,255)"]);
        } else {
            colours.push(obj.color);
        }
    
        colours = [colours[0].slice(0, obj.data[0].values.length)];
        coloursHighlight = [coloursHighlight[0].slice(0, obj.data[0].values.length)];
    
        var heightBar = 0;
        if(title != null && subtitle != null)
            heightBar = (height*0.8) / (2*numValues-0.5);
        else if(title == null && subtitle == null)
            heightBar = height / (2*numValues);
        else heightBar = 0.85*height / (2*numValues);
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var labelWidth = 0;
        var sizeOfLabels = [];
    
        var legendWidth = 0;
        var sizeOfLegends = [];
    
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLabels.push(this.getBBox().width);
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("l2")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLegends.push(this.getBBox().width);
                legendWidth = Math.ceil(Math.max(legendWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "middle")
            .attr("x", function(d,i){
                return labelWidth - sizeOfLabels[i]
            })
            .attr("y", function(d,i){
                if(title != null && subtitle != null)
                    return height*0.15+ (heightBar*obj.data[0].values.length)/2 +(+height*0.85)/obj.data.length*i;
                else if(title == null && subtitle == null)
                    return height*0.05+(heightBar*obj.data[0].values.length)/2 +(+height*0.95)/obj.data.length*i;
                else return height*0.1+(heightBar*obj.data[0].values.length)/2 +(+height*0.9)/obj.data.length*i;
            })
            .text(function(d){return d});
    
        var xRange = d3.scaleLinear()
            .range([0, width-labelWidth-legendWidth-width*0.1-margin*2])
            .domain([0, maxValue]);
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.84;
        else if(title == null && subtitle == null)
            tickSize = -height*0.94;
        else tickSize = -height*0.89;
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform", function(){
                return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
            })
            .call(make_x_gridlines(xRange)
            .tickSize(tickSize)
            .tickFormat("")
        );
    
        svg.selectAll("label")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("x", width*0.96)
            .attr("y", function(d, i){
                return height*0.2*(i+1)
            })
            .attr("font-size", Math.min(width, height)*0.025)
            .attr("text-anchor", "end")
            .attr("alignment-baseline", "hanging");
    
        svg.selectAll("rectangulo")
            .data(colours[0])
            .enter()
            .append("rect")
            .style("fill", function(d, i){return d})
            .attr("x", width*0.97)
            .attr("y", function(d,i){
                return height*0.2*(i+1)
            })
            .attr("height", Math.min(width, height)*0.03)
            .attr("width", Math.min(width, height)*0.03)
            .style("stroke-width", 0)
            .style("stroke", 0);
        
        
        var ind = 0;
        var inde=0;
        for(data of obj.data){
            for(v of data.values){
                svg.append("rect")
                    .attr("stroke-width", "0")
                    .attr("fill", function(){
                        if(data.highlight != null && data.highlight == true){
                            return coloursHighlight[0][ind];
                        } else {
                            return colours[0][ind];
                        }
                    })
                    .attr("x", margin+labelWidth)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return height*0.15+ ind*heightBar + inde*(height*0.85)/obj.data.length;
                        else if(title == null && subtitle == null)
                            return height*0.05+ ind*heightBar + inde*(height*0.95)/obj.data.length;
                        else return height*0.1+ ind*heightBar + inde*(height*0.9)/obj.data.length;
                    })
                    .attr("height", heightBar)
                    .attr("width", function(){
                        return xRange(v);
                    });
                    ind++;
            }
            inde++;
            ind=0;
        }
    
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform","translate("+ (labelWidth+margin) +"," + height*0.95 + ")")
            .call(axisX);
    
    
    
    }
    
    function groupedVerticalBarChart(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        var coloursHighlight = [];
        var margin = 10;
        var longest_label = "";
        var longest_legend = "";
        var maxValue = 0;
        var numValues = 0;
    
        if(obj.data.length > 4){
            obj.data = obj.data.slice(0, 4);
        }
    
        for(const item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
            values.push(item.values);
            numValues += item.values.length;
            if(Math.max.apply(null, item.values) > maxValue) maxValue = Math.max.apply(null, item.values);
        }
        for(const s of obj.legend){
            if(s.length > longest_legend.length) longest_legend = s;
        }
    
        if(obj.colorHighlight == null){
            coloursHighlight.push(["rgb(63.75,0,0)", "rgb(127.5,0,0)", "rgb(191.25,0,0)", "rgb(255,0,0)"]);
        } else {
            coloursHighlight.push(obj.colorHighlight);
        }
        if(obj.color == null){
            colours.push(["rgb(0,0,63.75)", "rgb(0,0,127.5)", "rgb(0,0,191.25)", "rgb(0,0,255)"]);
        } else {
            colours.push(obj.color);
        }
    
        colours = [colours[0].slice(0, obj.data[0].values.length)];
        coloursHighlight = [coloursHighlight[0].slice(0, obj.data[0].values.length)];
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var legendWidth = 0;
        var sizeOfLegends = [];
        svg.selectAll("l")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLegends.push(this.getBBox().width);
                legendWidth = Math.ceil(Math.max(legendWidth, this.getBBox().width));
            }).remove();
    
        var widthBar = (width*0.9-legendWidth)/(numValues+obj.data.length*2);
    
        svg.selectAll("label")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("x", width*0.96)
            .attr("y", function(d, i){
                return height*0.2*(i+1)
            })
            .attr("font-size", Math.min(width, height)*0.025)
            .attr("text-anchor", "end")
            .attr("alignment-baseline", "hanging");
    
        svg.selectAll("rectangulo")
            .data(colours[0])
            .enter()
            .append("rect")
            .style("fill", function(d, i){return d})
            .attr("x", width*0.97)
            .attr("y", function(d,i){
                return height*0.2*(i+1)
            })
            .attr("height", Math.min(width, height)*0.03)
            .attr("width", Math.min(width, height)*0.03)
            .style("stroke-width", 0)
            .style("stroke", 0);
    
        var yRange = null;
        
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, maxValue]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, maxValue]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, maxValue]);
    
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";            
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
            .tickSize(-width*0.8+legendWidth)
            .tickFormat("")
        );
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .style("font-size", Math.min(width,height)*0.03)
            .call(axisY);
            
        
        var ind = 0;
        var inde=0;
        for(data of obj.data){
            for(v of data.values){
                svg.append("rect")
                    .attr("stroke-width", "0")
                    .attr("fill", function(){
                        if(data.highlight != null && data.highlight == true){
                            return coloursHighlight[0][ind];
                        } else {
                            return colours[0][ind];
                        }
                    })
                    .attr("x", function(){
                        return width*0.1 + widthBar + ind*widthBar + inde*widthBar*obj.data[0].values.length +widthBar*inde;
                    })
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                           return height*0.15+yRange(v);
                        else if(title == null && subtitle == null)
                            return height*0.05+yRange(v);
                        else
                            return height*0.1+yRange(v);
                    })
                    .attr("height", function(){
                        return yRange(0)-yRange(v);
                    })
                    .attr("width", widthBar);
                    ind++;
            }
            inde++;
            ind=0;
        }
    
        svg.selectAll("p")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "middle")
            .attr("x", function(d,i){
                return width*0.1 + widthBar + i*widthBar*obj.data[0].values.length +widthBar*i + widthBar*obj.data[0].values.length/2;
            })
            .attr("y", height*0.95)
            .attr("font-size", Math.min(width,height)*0.03)
            .text(function(d){return d});
    }
    
    function groupedHorizontalBoxPlot(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
        
        var labels = [];
        var values = [];
        var colours = [];
        var coloursBorder = [];
        var coloursHighlight = [];
        var coloursBorderHighlight = [];
        var margin = 10;
        var longest_label = "";
        var longest_legend = "";
        var maxValue = 0;
        var numValues = 0;
    
        if(obj.data.length > 4){
            obj.data = obj.data.slice(0, 4);
        }
    
        for(const item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
            values.push(item.values);
            numValues += item.values.length;
            for(const d of item.values){
                if(d.max > maxValue) maxValue = d.max;
                if(d.outliers != null){
                    if(Math.max.apply(null, d.outliers) > maxValue) maxValue = Math.max.apply(null, d.outliers);
                }
            }
        }
    
        if(obj.color != null){
            colours = obj.color;
        } else {
            colours = ["rgb(0,0,50.75)", "rgb(0,0,140.5)", "rgb(0,0,200)", "rgb(0,0,255)"];
        }
    
        if(obj.colorBorder != null){
            coloursBorder = obj.colorBorder;
        } else {
            coloursBorder = ["rgb(0,0,255)", "rgb(0,0,255)", "rgb(0,0,20)", "rgb(0,0,20)"];
        }
    
        if(obj.colorHighlight != null){
            coloursHighlight = obj.colorHighlight;
        } else {
            coloursHighlight = ["rgb(50.75,0,0)", "rgb(140.5,0,0)", "rgb(200,0,0)", "rgb(255,0,0)"];
        }
    
        if(obj.colorBorderHighlight != null){
            coloursBorderHighlight = obj.colorBorderHighlight;
        } else {
            coloursBorderHighlight = ["rgb(255,0,0)", "rgb(255,0,0)", "rgb(0,0,20)", "rgb(0,0,20)"];
        }
    
        colours = [colours.slice(0, obj.data[0].values.length)];
        coloursHighlight = [coloursHighlight.slice(0, obj.data[0].values.length)];
        coloursBorder = [coloursBorder.slice(0, obj.data[0].values.length)];
        coloursBorderHighlight = [coloursBorderHighlight.slice(0, obj.data[0].values.length)];
    
        for(const s of obj.legend){
            if(s.length > longest_legend.length) longest_legend = s;
        }
    
        var heightBar = 0;
        if(title != null && subtitle != null)
            heightBar = (height*0.8) / (2*numValues-0.5);
        else if(title == null && subtitle == null)
            heightBar = height / (2*numValues);
        else heightBar = 0.85*height / (2*numValues);
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        }
    
        var labelWidth = 0;
        var sizeOfLabels = [];
    
        var legendWidth = 0;
        var sizeOfLegends = [];
    
        svg.selectAll("l")
            .data(labels)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLabels.push(this.getBBox().width);
                labelWidth = Math.ceil(Math.max(labelWidth, this.getBBox().width));
            }).remove();
    
        svg.selectAll("l2")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLegends.push(this.getBBox().width);
                legendWidth = Math.ceil(Math.max(legendWidth, this.getBBox().width));
            }).remove();
        
        svg.selectAll("label")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "start")
            .attr("alignment-baseline", "middle")
            .attr("x", function(d,i){
                return labelWidth - sizeOfLabels[i]
            })
            .attr("y", function(d,i){
                if(title != null && subtitle != null)
                    return height*0.15+ (heightBar*obj.data[0].values.length)/2 +(+height*0.85)/obj.data.length*i;
                else if(title == null && subtitle == null)
                    return height*0.05+(heightBar*obj.data[0].values.length)/2 +(+height*0.95)/obj.data.length*i;
                else return height*0.1+(heightBar*obj.data[0].values.length)/2 +(+height*0.9)/obj.data.length*i;
            })
            .text(function(d){return d}); 
    
        var xRange = d3.scaleLinear()
            .range([0, width-labelWidth-legendWidth-width*0.1-margin*2])
            .domain([0, maxValue]);
    
        var tickSize = 0;
        if(title != null && subtitle != null)
            tickSize = -height*0.84;
        else if(title == null && subtitle == null)
            tickSize = -height*0.94;
        else tickSize = -height*0.89;
    
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform", function(){
                return "translate("+ (labelWidth+margin) +"," + height*0.95 + ")"
            })
            .call(make_x_gridlines(xRange)
            .tickSize(tickSize)
            .tickFormat("")
        );
    
        svg.selectAll("label")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("x", width*0.96)
            .attr("y", function(d, i){
                return height*0.2*(i+1)
            })
            .attr("font-size", Math.min(width, height)*0.025)
            .attr("text-anchor", "end")
            .attr("alignment-baseline", "hanging");
    
        svg.selectAll("rectangulo")
            .data(colours[0])
            .enter()
            .append("rect")
            .style("fill", function(d, i){return d})
            .attr("x", width*0.97)
            .attr("y", function(d,i){
                return height*0.2*(i+1)
            })
            .attr("height", Math.min(width, height)*0.03)
            .attr("width", Math.min(width, height)*0.03)
            .style("stroke-width", 0)
            .style("stroke", 0);
    
        var axisX = d3.axisBottom(xRange);
        svg.append("g")
            .attr("transform","translate("+ (labelWidth+margin) +"," + height*0.95 + ")")
            .call(axisX);
    
        for(let index = 0; index < obj.data.length; index++){
            var d = obj.data[index];
            for(let index2 = 0; index2 < d.values.length; index2++){
                var v = d.values[index2];
    
                svg.append("line")
                    .attr("x1", labelWidth+margin + xRange(v.min))
                    .attr("x2", labelWidth+margin + xRange(v.max))
                    .attr("y1", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + heightBar/2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + heightBar/2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + heightBar/2 + (height*0.9)/obj.data.length*index;
                    })
                    .attr("y2", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + heightBar/2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + heightBar/2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + heightBar/2 + (height*0.9)/obj.data.length*index;
                    })
                    .style("stroke", "black")
                    .style("stroke-width", 2);
    
                svg.append("text")
                    .text("|")
                    .attr("x", labelWidth+margin+xRange(v.min))
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + heightBar/2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + heightBar/2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + heightBar/2 + (height*0.9)/obj.data.length*index;
                    })
                    .attr("font-size", Math.min(width, height)*0.05)
                    .attr("text-anchor", "middle")
                    .attr("alignment-baseline", "middle");
                
                svg.append("text")
                    .text("|")
                    .attr("x", labelWidth+margin+xRange(v.max))
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + heightBar/2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + heightBar/2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + heightBar/2 + (height*0.9)/obj.data.length*index;
                    })
                    .attr("font-size", Math.min(width, height)*0.05)
                    .attr("text-anchor", "middle")
                    .attr("alignment-baseline", "middle");
    
                svg.append("rect")
                    .attr("stroke-width", "4")
                    .attr("fill", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursHighlight[0][index2];
                        else 
                            return colours[0][index2];
                    })
                    .attr("stroke", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursBorderHighlight[0][index2];
                        else
                            return coloursBorder[0][index2];
                    })
                    .attr("x", xRange(v.q1) + labelWidth + margin)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + (height*0.9)/obj.data.length*index;
                    })
                    .attr("height", heightBar)
                    .attr("width", xRange(v.q3 - v.q1));
                
            svg.append("rect")
                .attr("stroke-width", "4")
                .attr("fill", function(){
                    if(d.highlight != null && d.highlight == true)
                        return coloursHighlight[0][index2];
                    else 
                        return colours[0][index2];
                })
                .attr("stroke", function(){
                    if(d.highlight != null && d.highlight == true)
                        return coloursBorderHighlight[0][index2];
                    else
                        return coloursBorder[0][index2];
                })
                .attr("x", xRange(v.median) + labelWidth+margin)
                .attr("y", function(){
                    if(title != null && subtitle != null)
                        return height*0.15 + heightBar*index2 + (height*0.85)/obj.data.length*index;
                    else if(title == null && subtitle == null)
                        return height*0.05 + heightBar*index2 + (height*0.95)/obj.data.length*index;
                    else
                        return height*0.1 + heightBar*index2 + (height*0.9)/obj.data.length*index;
                })
                .attr("height", heightBar)
                .attr("width", 1);
    
            if(v.outliers == null) continue;
            for(v of v.outliers){
                svg.append("circle")
                    .attr("cx", labelWidth+margin + xRange(v))
                    .attr("cy", function(){
                        if(title != null && subtitle != null)
                            return height*0.15 + heightBar*index2 + heightBar/2 + (height*0.85)/obj.data.length*index;
                        else if(title == null && subtitle == null)
                            return height*0.05 + heightBar*index2 + heightBar/2 + (height*0.95)/obj.data.length*index;
                        else
                            return height*0.1 + heightBar*index2 + heightBar/2 + (height*0.9)/obj.data.length*index;
                    })
                    .attr("r", Math.min(width, height)*0.01)
                    .attr("stroke", "black")
                    .attr("stroke-width", 1)
                    .attr("fill", "none");
            }
        }
    
        }
        
    }
    
    function groupedVerticalBoxPlot(id, width, height, data){
        var obj = JSON.parse(data);
        var title = obj.title;
        var subtitle = obj.subtitle;
        
        var svg = d3.select(id).append("svg")
            .attr("width", width)
            .attr("height", height);
        if(obj.desc != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(obj.desc);
        }else if(subtitle != null){
            svg.attr("aria-labelledby", "desc");
            svg.append("desc").text(subtitle);
        }
    
        var labels = [];
        var values = [];
        var colours = [];
        var coloursBorder = [];
        var coloursHighlight = [];
        var coloursBorderHighlight = [];
        var margin = 10;
        var longest_label = "";
        var longest_legend = "";
        var maxValue = 0;
        var numValues = 0;
    
        if(obj.data.length > 4){
            obj.data = obj.data.slice(0, 4);
        }
    
        for(const item of obj.data){
            labels.push(item.label);
            if(item.label.length > longest_label.length) longest_label = item.label;
            values.push(item.values);
            numValues += item.values.length;
            for(const d of item.values){
                if(d.max > maxValue) maxValue = d.max;
                if(d.outliers != null){
                    if(Math.max.apply(null, d.outliers) > maxValue) maxValue = Math.max.apply(null, d.outliers);
                }
            }
        }
        for(const s of obj.legend){
            if(s.length > longest_legend.length) longest_legend = s;
        }
    
        
        if(obj.color != null){
            colours = obj.color;
        } else {
            colours = ["rgb(0,0,50.75)", "rgb(0,0,140.5)", "rgb(0,0,200)", "rgb(0,0,255)"];
        }
    
        if(obj.colorBorder != null){
            coloursBorder = obj.colorBorder;
        } else {
            coloursBorder = ["rgb(0,0,255)", "rgb(0,0,255)", "rgb(0,0,20)", "rgb(0,0,20)"];
        }
    
        if(obj.colorHighlight != null){
            coloursHighlight = obj.colorHighlight;
        } else {
            coloursHighlight = ["rgb(50.75,0,0)", "rgb(140.5,0,0)", "rgb(200,0,0)", "rgb(255,0,0)"];
        }
    
        if(obj.colorBorderHighlight != null){
            coloursBorderHighlight = obj.colorBorderHighlight;
        } else {
            coloursBorderHighlight = ["rgb(255,0,0)", "rgb(255,0,0)", "rgb(0,0,20)", "rgb(0,0,20)"];
        }
    
        colours = [colours.slice(0, obj.data[0].values.length)];
        coloursHighlight = [coloursHighlight.slice(0, obj.data[0].values.length)];
        coloursBorder = [coloursBorder.slice(0, obj.data[0].values.length)];
        coloursBorderHighlight = [coloursBorderHighlight.slice(0, obj.data[0].values.length)];
    
    
        for(const s of obj.legend){
            if(s.length > longest_legend.length) longest_legend = s;
        }
    
        if(title!=null){
            svg.append("text").text(title)
                .attr("x", width/2)
                .attr("y",height*0.05)
                .attr("font-size", Math.min(width,height)*0.04)
                .attr("text-decoration", "underline")
                .attr("text-anchor", "middle");
        }
    
        if(subtitle != null){
            if(title != null){
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.1)
                    .attr("text-anchor", "middle");
            }else{
                svg.append("text").text(subtitle)
                    .attr("x", width/2)
                    .attr("y", height*0.05)
                    .attr("text-anchor", "middle");
            }
        } 
    
        var legendWidth = 0;
        var sizeOfLegends = [];
        svg.selectAll("l")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .each(function(){
                sizeOfLegends.push(this.getBBox().width);
                legendWidth = Math.ceil(Math.max(legendWidth, this.getBBox().width));
            }).remove();
    
        var widthBar = (width*0.9-legendWidth)/(numValues+obj.data.length*2);
    
        svg.selectAll("label")
            .data(obj.legend)
            .enter()
            .append("text")
            .text(function(d){return d})
            .attr("x", width*0.96)
            .attr("y", function(d, i){
                return height*0.2*(i+1)
            })
            .attr("font-size", Math.min(width, height)*0.025)
            .attr("text-anchor", "end")
            .attr("alignment-baseline", "hanging");
    
        svg.selectAll("rectangulo")
            .data(colours[0])
            .enter()
            .append("rect")
            .style("fill", function(d, i){return d})
            .attr("x", width*0.97)
            .attr("y", function(d,i){
                return height*0.2*(i+1)
            })
            .attr("height", Math.min(width, height)*0.03)
            .attr("width", Math.min(width, height)*0.03)
            .style("stroke-width", 0)
            .style("stroke", 0); 
    
        var widthBar = (width*0.9-legendWidth)/(numValues+obj.data.length*2);
    
        var yRange = null;
        
        if(title != null && subtitle != null)
            yRange = d3.scaleLinear().range([height*0.75, 0])
                .domain([0, maxValue]);
        else if (title == null && subtitle == null)
            yRange = d3.scaleLinear().range([height*0.85, 0])
                .domain([0, maxValue]);
        else yRange = d3.scaleLinear().range([height*0.8, 0])
                .domain([0, maxValue]);   
        
        svg.append("g")
            .attr("stroke", "lightgrey")
            .attr("stroke-opacity", 0.2)
            .attr("shape-rendering", "crispEdges")
            .attr("transform",function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";            
            })
            //.style("stroke-dasharray",("3,3"))
            .call(make_y_gridlines(yRange)
            .tickSize(-width*0.8+legendWidth)
            .tickFormat("")
        );
    
        var axisY = d3.axisLeft(yRange);
        svg.append("g")
            .attr("transform", function(){
                if(title != null && subtitle != null)
                    return "translate("+width*0.1+"," + height*0.15 + ")";
                else if(title == null && subtitle == null)
                    return "translate("+width*0.1+"," + height*0.05 + ")";
                else
                    return "translate("+width*0.1+"," + height*0.1 + ")";
            })
            .style("font-size", Math.min(width,height)*0.03)
            .call(axisY); 
    
        svg.selectAll("p")
            .data(labels)
            .enter()
            .append("text")
            .attr("text-anchor", "middle")
            .attr("x", function(d,i){
                return width*0.1 + widthBar + i*widthBar*obj.data[0].values.length +widthBar*i + widthBar*obj.data[0].values.length/2;
            })
            .attr("y", height*0.95)
            .attr("font-size", Math.min(width,height)*0.03)
            .text(function(d){return d});
            
    
    
        for(let index = 0; index < obj.data.length; index++){
            var d = obj.data[index];
            for(let index2 = 0; index2 < d.values.length; index2++){
                var v = d.values[index2];
        
                svg.append("line")
                    .attr("x1", width*0.1 + widthBar*1.5 + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("x2", width*0.1 + widthBar*1.5 + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("y1", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.min));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.min));
                        else
                            return (height*0.10 + yRange(v.min));
                    })
                    .attr("y2", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.max));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.max));
                        else
                            return (height*0.1 + yRange(v.max));
                    })
                    .style("stroke", "black")
                    .style("stroke-width", 2);
        
                svg.append("text")
                    .text("-")
                    .attr("x", width*0.1 + widthBar*1.5 + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.min));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.min));
                        else
                            return (height*0.10 + yRange(v.min));
                    })
                    .attr("font-size", Math.min(width, height)*0.05)
                    .attr("text-anchor", "middle")
                    .attr("alignment-baseline", "middle");
                   
                svg.append("text")
                    .text("-")
                    .attr("x", width*0.1 + widthBar*1.5 + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.max));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.max));
                        else
                            return (height*0.1 + yRange(v.max));
                    })
                    .attr("font-size", Math.min(width, height)*0.05)
                    .attr("text-anchor", "middle")
                    .attr("alignment-baseline", "middle");
        
                svg.append("rect")
                    .attr("stroke-width", "4")
                    .attr("fill", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursHighlight[0][index2];
                        else 
                            return colours[0][index2];
                    })
                    .attr("stroke", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursBorderHighlight[0][index2];
                        else
                            return coloursBorder[0][index2];
                    })
                    .attr("x", width*0.1 + widthBar + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.q3));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.q3));
                        else
                            return (height*0.1 + yRange(v.q3));
                    })
                    .attr("height", yRange(0) - yRange(v.q3-v.q1))
                    .attr("width", widthBar);
                    
                svg.append("rect")
                    .attr("stroke-width", "4")
                    .attr("fill", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursHighlight[0][index2];
                        else 
                            return colours[0][index2];
                    })
                    .attr("stroke", function(){
                        if(d.highlight != null && d.highlight == true)
                            return coloursBorderHighlight[0][index2];
                        else
                            return coloursBorder[0][index2];
                    })
                    .attr("x", width*0.1 + widthBar + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                    .attr("y", function(){
                        if(title != null && subtitle != null)
                            return (height*0.15 + yRange(v.median));
                        else if(title == null && subtitle == null)
                            return (height*0.05 + yRange(v.median));
                        else
                            return (height*0.1 + yRange(v.median));
                    })
                    .attr("height", 1)
                    .attr("width", widthBar);
        
                if(v.outliers == null) continue;
                for(o of v.outliers){
                    svg.append("circle")
                        .attr("cx", width*0.1 + widthBar + index2*widthBar + index*widthBar + index*widthBar*d.values.length)
                        .attr("cy", function(){
                            if(title != null && subtitle != null)
                                return (height*0.15 + yRange(o));
                            else if(title == null && subtitle == null)
                                return (height*0.05 + yRange(o));
                            else
                                return (height*0.1 + yRange(o));
                        })
                        .attr("r", Math.min(width, height)*0.01)
                        .attr("stroke", "black")
                        .attr("stroke-width", 1)
                        .attr("fill", "none");
                }
            }
        }
    }
    </script>';
}

function addGraph($post){
    $res = $post;

    preg_match_all("/\[\-charts#(.*?)#(.*?)#(.*?)#(.*?)\-\]/", $post, $busqueda);
    $i = 0;


    foreach($busqueda[0] as $c){
        $values = $c;
        $values = substr($values, 2);
        $values = rtrim($values, "-]");
        $dat = explode("#", $values);

        if($dat[1] == "horizontalBarGraph"){
            $res = str_replace($c,'
            <div id="wp-charts-plugin-graph-'.$i.'"></div>
            <script>horizontalBarGraph("#wp-charts-plugin-graph-'.$i.'", '.$dat[2].','.$dat[3].',\''.$dat[4].'\')</script>
            ',$res);
        }elseif($dat[1] == "verticalBarGraph"){
            $res = str_replace($c,'
            <div id="wp-charts-plugin-graph-'.$i.'"></div>
            <script>verticalBarGraph("#wp-charts-plugin-graph-'.$i.'", '.$dat[2].','.$dat[3].',\''.$dat[4].'\')</script>
            ',$res);
        }elseif($dat[1] == "horizontalBarGraphNoSpace"){
            $res = str_replace($c,'
            <div id="wp-charts-plugin-graph-'.$i.'"></div>
            <script>horizontalBarGraphNoSpace("#wp-charts-plugin-graph-'.$i.'", '.$dat[2].','.$dat[3].',\''.$dat[4].'\')</script>
            ',$res);
        }elseif($dat[1] == "verticalBarGraphNoSpace"){
            $res = str_replace($c,'
            <div id="wp-charts-plugin-graph-'.$i.'"></div>
            <script>verticalBarGraphNoSpace("#wp-charts-plugin-graph-'.$i.'", '.$dat[2].','.$dat[3].',\''.$dat[4].'\')</script>
            ',$res);
        }
        $i++;
    }

    return $res;
}

add_filter("the_content", "addGraph");
add_filter("wp_head", "add_script");

?>