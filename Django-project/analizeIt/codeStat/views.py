import json
import requests
from django.shortcuts import render, redirect, Http404

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


def codePage(request):
    dicc = {}

    if not "q" in request.GET:
        return redirect("/")
    elif request.GET["q"] == "":
        return redirect("/")
    else:
        name_project = request.GET["q"]

    jsonRepo = getApi("https://api.github.com/repos/" + name_project)
    if jsonRepo == "private":
        return redirect("/isPrivate/")
    jsonLanguage = getApi("https://api.github.com/repos/" + name_project + "/languages")
    jsonContributors = getApi("https://api.github.com/repos/" + name_project + "/contributors")

    languagesNumbersOfLine = {}
    languagesNumbersOfLine["title"] = "Number of lines"
    languagesNumbersOfLine["subtitle"] = "Number of lines of each languages in the project"
    languagesNumbersOfLine["data"] = []
    i = 0
    for language, lines in jsonLanguage.items():
        if i >= 10:
            break
        languagesNumbersOfLine["data"].append(
            {
                "label": language,
                "value": lines
            }
        )
        i += 1

    contributionsPerUser = {}
    contributionsPerUser["title"] = "Contributions per user"
    contributionsPerUser["subtitle"] = "Number of contributions made by users on master"
    contributionsPerUser["data"] = []
    i = 0
    for contributor in jsonContributors:
        if i >= 10: 
            break
        contributionsPerUser["data"].append(
            {
                "label": contributor["login"],
                "value": contributor["contributions"]
            }
        )
        i += 1


    dicc["languages"] = json.dumps(languagesNumbersOfLine)
    dicc["mainLanguage"] = jsonRepo["language"]
    dicc["title"] = name_project
    dicc["contributions"] = json.dumps(contributionsPerUser)
    return render(request, "codeStat/code.html", dicc)
