import json
import urllib
import re
import requests
from django.shortcuts import render, Http404, redirect

# Create your views here.
def getApi(url):
    try:
        jsonD = requests.get(url).json()
    except:
        raise Http404()

    if "message" in jsonD:
        if jsonD["message"] == "Not Found":
            return "private"
    return jsonD

def getTests(url):
    try:
        f = urllib.request.urlopen(url)
        file = f.read()
        num_lines = sum(1 for line in file)
        f.close()
    except:
        raise Http404

    return (num_lines, len(re.findall("def\\s[^\\s]+\\(.*\\)\\:", file.decode('utf-8'))))

def getLines(url):
    try:
        f = urllib.request.urlopen(url)
        file = f.read()
        f.close()
        num_lines = sum(1 for lines in file)
        return num_lines
    except Exception:
        return 0

def tests(request):
    dicc = {}
    if not "q" in request.GET:
        return redirect("/")
    elif request.GET["q"] == "":
        return redirect("/")
    else:
        name_project = request.GET["q"]

    if request.GET.get("b"):
        branch = request.GET["b"]
    else:
        branch = 'master'

    jsonLastCommit = getApi("https://api.github.com/repos/" + name_project + "/commits/" + branch)
    if jsonLastCommit == "private":
        return redirect("/isPrivate/")
    sha = jsonLastCommit["sha"]
    jsonFiles = getApi("https://api.github.com/repos/" + name_project + "/git/trees/" + sha + "?recursive=1")

    codeRatios = {}

    test = {}
    test["title"] = "Tests"
    test["subtitle"] = "Number of tests per app in branch " + branch
    test["data"] = []
    for j in jsonFiles["tree"]:
        if not j["path"].endswith("tests.py"):
            continue
        appName = j["path"][:-9]

        linesOfTests, t = getTests("https://raw.githubusercontent.com/"+name_project+"/"+branch+"/"+j["path"])
        test["data"].append({
            "label": appName,
            "value": t
        })

        if not appName in codeRatios:
            codeInApp = 0
            codeInApp += getLines("https://raw.githubusercontent.com/"+name_project+"/"+branch+"/"+j["path"][:-9]+ "/models.py")
            codeInApp += getLines("https://raw.githubusercontent.com/"+name_project+"/"+branch+"/"+j["path"][:-9]+ "/forms.py")
            codeInApp += getLines("https://raw.githubusercontent.com/"+name_project+"/"+branch+"/"+j["path"][:-9]+ "/views.py")
            codeRatios[appName] = [codeInApp, linesOfTests, int(linesOfTests/codeInApp)]


    linesOfCodeGraph = {}
    linesOfCodeGraph["title"] = "Lines of code"
    linesOfCodeGraph["subtitle"] = "Lines of code of app and tests per module in branch " + branch
    linesOfCodeGraph["legend"] = ["Functionality code", "Test code"]
    linesOfCodeGraph["data"] = []
    for app, lines in codeRatios.items():
        linesOfCodeGraph["data"].append({
            "label": app,
            "values": [lines[0], lines[1]]
        })


    jsonBranches = getApi("https://api.github.com/repos/" + name_project + "/branches")
    dicc["branches"] = []
    for j in jsonBranches:
        dicc["branches"].append(j["name"])

    dicc["title"] = name_project
    dicc["tests"] = json.dumps(test)
    dicc["linesOfCode"] = codeRatios
    dicc["linesOfCodeGraph"] = json.dumps(linesOfCodeGraph)
    dicc["currentBranch"] = branch
    return render(request, "testStat/tests.html", dicc)
