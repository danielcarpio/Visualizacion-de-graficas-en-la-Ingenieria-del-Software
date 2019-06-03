import datetime
import json
import requests
from django.shortcuts import render, redirect, Http404

def getApi(url):
    try:
        jsonD = requests.get(url).json()
    except:
        raise Http404()

    if "message" in jsonD:
        if jsonD["message"] == "Not Found":
            return "private"
    return jsonD

# Create your views here.
def commit(request):
    if not "q" in request.GET:
        return redirect("/")
    elif request.GET["q"] == "":
        return redirect("/")
    else:
        name_project = request.GET["q"]
    dicc = {}

    if not "b" in request.GET:
        branch = 'master'
    else:
        branch = request.GET["b"]

    commitsPerDay = [0, 0, 0, 0, 0, 0, 0]
    t = datetime.date.today() - datetime.timedelta(days=7)
    jsonCommits = getApi("https://api.github.com/repos/" + name_project + "/commits?since=" + t.strftime('%Y-%m-%d') + "T00:00:00Z" + "&until=" + datetime.date.today().strftime('%Y-%m-%d') + "T23:59:59Z" + "&sha="+branch)
    if jsonCommits == "private":
        return redirect("/isPrivate/")

    firstDay = int(t.strftime("%d"))
    for j in jsonCommits:
        if j["commit"]["committer"]["date"][8:10] == str(firstDay):
            commitsPerDay[0] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+1):
            commitsPerDay[1] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+2):
            commitsPerDay[2] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+3):
            commitsPerDay[3] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+4):
            commitsPerDay[4] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+5):
            commitsPerDay[5] += 1
        elif j["commit"]["committer"]["date"][8:10] == str(firstDay+6):
            commitsPerDay[6] += 1

    graficaLineas = {
        "title": "Commits this week",
        "subtitle": "Number of commits made by users this week",
        "labels": [],
        "data": []
    }
    graficaLineas["labels"] = [firstDay, firstDay+1, firstDay+2, firstDay+3, firstDay+4, firstDay+5, firstDay+6]
    graficaLineas["data"].append({
        "label": "Commits",
        "values": commitsPerDay
    })


    jsonBranches = getApi("https://api.github.com/repos/" + name_project + "/branches")
    dicc["branches"] = []
    for j in jsonBranches:
        dicc["branches"].append(j["name"])

    dicc["currentBranch"] = branch
    dicc["title"] = name_project
    dicc["graficaCommits"] = json.dumps(graficaLineas)
    return render(request, 'commitStat/commit.html', dicc)
