#  Contribution Guidelines

We want to start off by saying thank you for using phpCollab. This project is a labor of love, and we appreciate the work done by all who catch bugs, make performance improvements and help with documentation. Every contribution is meaningful, so thank you for participating. That being said, here are a few guidelines that we ask you to follow so we can successfully address your patch.

The goal of this guide is to, very simply put, help you learn how to catch fish. 

Shed all of your fears of making a mistake as part of your contributions to the project. Practice makes perfect and
we are all here to help you as much as we can so you can ultimately become an independent contributor to the project. 

It's OK to make mistakes and it does take some time, effort and energy to improve the state of proposals and contributions. So do not be discouraged, keep at it and if you find anything that is unclear along the way, do not hesitate to ask questions. 

The overarching theme of this guide starts with the following questions:

1. What is the phpCollab project policy on accepting contributions?
2. How may one, seriously and in a step-wise fashion, get started with contributions? 

## What do I work on?
 
Certain number of projects in open source try to advertise work items and tasks which they think might be [good candidates for contributions](https://github.com/spring-projects/spring-boot/issues?q=is%3Aopen+is%3Aissue+label%3A%22status%3A+ideal-for-contribution%22). This is generally and often *not* the phpCollab project policy. The policy is much simpler than that. 

It goes something like this:

> Everything is ideal for contributions.

In other words, 

- There is no *"We vs. You"*. 
- There is no *"Some folks can only fix certain issues and some can't"*. 
- There is no *"Person X made the change; so X must fix it too"* (aka code ownership)
- There is no *"I am just a user; you are the developers"*.

Of course, if you are a newcomer to the project and have just begun to understand the ins and outs of the [phpCollab project codebase](https://github.com/phpcollab/phpcollab), there may certainly be areas in which you might find more comfortble to slowly get your feet wet. You're welcome to [ask for suggestions](https://phpcollab.slack.com/). For the most part, the work item you wish to work on should be something you find interesting and enjoyable with some degree of practicality.

Remember that you are deploying open source software, which means you have automatically become a project/community member and a potential maintainer, whether you realize it or not. Staying in *consume-only* mode generally leads to poor results.

## What can I work on?

All contributions are extremely welcomed with open arms regardless of shape, size and color. You may be interested in helping with fixing typos, writing documentation, authoring test cases, developing code, squashing bugs, etc. All is welcome. 

In other words, if you happen to come across a bothersome use case and/or something you consider a perfect candidate for improvement and attention, you are most definitely, aggressively and wholeheartedly encouraged to spend time and DNA to improve the quality of your phpCollab life. There is no point in silent suffering.

If you find that contributing to the project is at the moment out of your reach, don't worry. There are resources, [here](https://www.coursera.org/) and [here](../help/commercial-affiliates.md) that can provide training and support to get you started for the win.


## Do I need an issue first?

No. 

If you have already identified an enhancement or a bug, it is STRONGLY recommended that you simply submit a pull request to address the case. There is no need for special ceremony to create separate issues. The pull request **IS** the issue and it will be tracked and tagged as such. Remember that this is open source software in an open and collaborative community. It's not "Some folks report issues and some folks fix problems" software.

The issue tracker is only a simple communication tool to assess a given problematic case or enhancement request. It's not tracking anything, really. You are for the most part and at all costs encouraged to submit patches that fix the reported issue and remove pain, rather than waiting for someone to come along and fix it. As prescribed, there is no *"We vs. You"*.

Very simply put:

> You are the one you have been waiting for.

If you'd rather just report the issue to have others benefit from your discovery, it would be best if you could share the use case and the problem at hand via community mailing lists, chatrooms and the likes of StackOverflow.

## What if I am not a developer?

If you have already diagnosed a problem or have found a use case for an attractive improvement and feature, we strongly recommend you simply [walk down the path](https://github.com/phpcollab/phpcollab/graphs/contributors) of making that all a reality and *contribute* back. If you find yourself unable to do so, you will need to find and procure resources that can [teach you the how-to](https://www.coursera.org/) or [do it for you](../help/commercial-affiliates.md). Talk to your institution, company, organization, supervisor, boss, mentor, architect, etc and have them procure the right resources for the job.

Reporting an issue and hoping/waiting for someone else to magically come along and spend time, money and energy to provide a fix for you is never an acceptable strategy. Quit waiting for the light at the end of the tunnel and light it yourself.

If you insist on being treated like a user, you then need to revisit and realign expectations with [folks and resources](../help/commercial-affiliates.md) that can provide you with the treatment you need. All of the work that goes into the development of a rich comprehensive software platform is almost exclusively done on a voluntary basis. Therefor, if you have expectations that resemble something of a commercial support agreement with clauses that include promises, guarantee, SLAs and follow-ups, you simply need to level up with an entity that actually provides that sort of functionality.

## How do I know who's working on what?

- Follow the *WIP Pattern* and submit [early pull requests](https://ben.straub.cc/2015/04/02/wip-pull-request/). This is in fact the recommended strategy from Github:

> Pull Requests are a great way to start a conversation of a feature, so start one as soon as possible- even before you are finished with the code. Your team can comment on the feature as it evolves, instead of providing all their feedback at the very end.

Or put another way:

> You’re opening pull requests when you start work, not when you’re finished. 

There is of course the alternative: [ask](../help/mailing-lists.html).

## Can I backport a change to a maintenance branch?

Yes, absolutely. Provided the change fits the scope of the maintenance branch and its tracking release and assuming the branch is still under care, you are more than welcome to move changes across the codebase various branches as much as needed to remove pain and improve.  

## What if the change is too big?

Start by reviewing the [release policy](release-policy.html). The change you have in mind should fit the scope of the release that is planned. If needed, [please discuss](https://phpcollab.slack.com) the release schedule and policy with other community members to find alternative solutions and strategies for delivery.

## Is it worth it?

The phpCollab project generally operates based on its own [maintenance policy](). Before making changes, you want to cross check the phpCollab deployment you have today and ensure it is still and to what extent considered viable and maintained by the project.

## How do I get on the roadmap?

By simply delivering the change and having it get merged into the codebase relevant branches. There is no predefined roadmap for the project. Work items get completed based on community's availability, interest, time and money. **The roadmap is what you intend to work on.**

## How often are changed released?

You can review the [release schedule](https://github.com/phpcollab/phpcollab/milestones). Note that the dates specified for each milestone are somewhat tentative, and may be pushed around depending on need and severity. 

As for general contributions, patches in form of pull requests are generally merged as fast as possible provided they are in *good health*. This means a given pull request must pass a series of automated checks that examine style, tests and such before it becomes eligible for a merge. If your proposed pull request does not yet have the green marking, worry not. Keep pushing to the branch that contains your change to auto-update the pull request and make life green again.

If you find that the project is not moving forward at a pace you find reasonable, simply *ping* the pull request and gently remind someone to step forward and review the issue with you.

## How fast can I consume the change?

Typically, `SNAPSHOT` releases are published by the automatic [Travis CI process](https://travis-ci.org/phpcollab/phpcollab/builds). As soon as a patch is merged, you want to track its build status and once it turns green, you should be good to update snapshots in your build script. Practically, this process can take up to 50 minutes or less. Refer to the `README` file of the build script and the project documentation to learn how you may update to take advantage of the change.

## Functional Build

Before you do anything else, make sure you have a [functional build](Build-Process.html). 

## How do I do this?

In order to successfully finish this exercise you need:

1. [Git](https://git-scm.com/downloads)
2. An IDE/editor like [PhpStorm](https://www.jetbrains.com/phpstorm/download/), [Visual Studio Code](https://code.visualstudio.com) or whatever you prefer to use (Depending on the change, `vim` may be perfectly fine too)
3. [PHP](http://www.php.net/downloads.php)

### Fork the repository

First thing you need to do is to [fork the phpCollab repository](https://help.github.com/articles/fork-a-repo/) under your own account. The phpCollab repository is hosted on Github and is available [here](https://github.com/phpcollab/phpcollab).

### Clone repositories

There are much faster ways of cloning the codebase, but let's keep it simple for now:

```bash
git clone git@github.com:phpcollab/phpcollab.git
cd phpcollab
git remote add github-username git@github.com:github-username/phpcollab.git
git checkout master
```

Next, if you simply list the remotes you should see:

```bash
origin  git@github.com:phpcollab/phpcollab.git (fetch)
origin  git@github.com:phpcollab/phpcollab.git (push)
github-username  git@github.com:github-username/phpcollab.git (fetch)
github-username  git@github.com:github-username/phpcollab.git (push)
```

You want to isolate your changes inside individual topics branches and never commit anything to the `master` branch. The workflow more or less is the following:

1. Create topic branch.
2. Make changes and test.
3. Commit changes to branch.
4. Go back to #2 until you are satisfied.

> *Functional Build*
> 
> You may want to ensure the codebase can be built locally from source. [Follow this guide](developer/build-process.html) to learn more.

### Create branch

To create a topic branch for the change, execute:

```bash
git status
git checkout -b my-topic-branch-which-fixes-something
```

### Commit changes

When you're ready to commit changes after having made changes, execute:

```bash
git add --all && git commit -am "This change fixes a problem"
```

Note that the `--all` flag adds *all* modified files in the project directory. If you wish to pick and choose, you can either individually add files via a `git add fileName` command one at a time or perhaps, it might be best to simply opt for a GUI client such as [SourceTree](https://www.sourcetreeapp.com/) or [Git Extensions](https://github.com/gitextensions/gitextensions). 

### Push changes

Push your changes from the *local* branch to a *remote* branch of your own fork:

```bash
git push github-username my-topic-branch-which-fixes-something
```

### Submit pull request

Follow the [guide here](https://help.github.com/articles/about-pull-requests/) to create a pull request based on your branch to the phpCollab project. In this particular case, the *target* branch is the `master` branch because your own branch was created off of the `master` branch.
