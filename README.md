# moodle-local_cria

Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.

## Description
Cria is built specifically for Univerisities and other educational institutions that are looking at
creating bots for their students, staff and faculty. Building bots is as easy as uploading documents or pointing to a URL,
or creating questions within the interface.

This module is the front-end interface that allows you easily to create bots based on Large Language Models (LLM).
Currently, the only supported LLM is MS Azure OpenAI. However, it is possible to create providers for other AI systems 
such as Gemini, Llama, Bedrock etc.

## Dependencies
* [Moodle](https://download.moodle.org/) 3.11 or later
* [moodle-theme_cria](https://github.com/itinnovationyorku/moodle-theme_cria)
* [CriaBot](https://github.com/YorkUITInnovation/criabot)
* [Criadex](https://github.com/YorkUITInnovation/Criadex)
* [CriaParse](https://github.com/YorkUITInnovation/CriaParse)
* [CriaEmbed](https://github.com/YorkUITInnovation/criaembed)
* [Scrapper](https://github.com/amerkurev/scrapper)
* [Cohere AI](https://cohere.com) for reranking.
* [Qdrant](https://qdrant.tech/)
* [ConvertAPI](https://www.convertapi.com/) for document conversion. (Optional)

## Why Moodle?
Moodle is a popular open-source learning management system that is used by many educational institutions. Although, Cria
is not a learning management system, Moodle is used as a framework to develop the platform due to it's modular
architecture. Cria is built as a Moodle plugin to take advantage of the many features that Moodle offers such as user management,
authentication, security, roles, caching, permissions, and many more. Plus, we are extremely familiar with Moodle at York University.

## Installation
1. Download the zip file
2. Unzip the file in the {root}/local folder
3. Rename the folder to "cria"
4. Go to Site Administration > Notifications to install the plugin

## Configuration

### General
1. Go to Site Administration > Plugins > Local plugins > Cria
2. Enter the URL with the port number for each Cria component. For example, for CriaBot, you would enter `https://{your.domain}:25575`
3. Enter the API key for each Cria component.
4. Enter the API key for Cohere AI.
5. Enter the API key for ConvertAPI (Optional. Having ConvertAPI will allow you to use more preprocessing services within Cria)

### Azure OpenAI
Before you can configure the plugin, you must have an Azure Subscription and access to Azure OpenAI. You will need to 
create an Azure OpenAI resource and get the API key. You will also need to deploy models. 
[How to deploy Azure OpenAI models](https://docs.microsoft.com/en-us/azure/cognitive-services/azure-openai-deploy-models)

### Cohere AI
You will also need an account with [Cohere AI](https://cohere.com) and the API key. We suggest you purchase directly from
Cohere AI as they have a free tier that you can use to test the system.



