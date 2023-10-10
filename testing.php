<?php
require_once('../../config.php');
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3TokenizerConfig.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3Tokenizer.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Merges.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Vocab.php");

use local_cria\gpt;
use local_cria\Gpt3Tokenizer;
use local_cria\Gpt3TokenizerConfig;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/testing.php'),
    get_string('pluginname', 'local_cria'),
    'Testing',
    $context
);



//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$GPT = new gpt();

$config = new Gpt3TokenizerConfig();
$tokenizer = new Gpt3Tokenizer($config);
$text = "[Rob Finlayson] 09:07:07
Share it and keep working. But I won't be sending any confirmation to anybody until we're in agreement that we're on the right path.

[Aladin Alaily] 09:07:09
Okay. [Rob Finlayson] 09:07:07
Share it and keep working. But I won't be sending any confirmation to anybody until we're in agreement that we're on the right path.

[Aladin Alaily] 09:07:09
Okay.

[Aladin Alaily] 09:07:16
Sounds good sounds good, so is my accent a little too sick here, cause it looks like somebody set the closed captioning.

[Patrick Thibaudeau] 09:07:23
I did. I'm just testing it. No, I'm just testing it to see how it's worked.

[Aladin Alaily] 09:07:25
So, yeah, okay, that was funny. I saw close captioning.

[Patrick Thibaudeau] 09:07:29
So!

[Aladin Alaily] 09:07:31
I'm like, damn my, my accent would be really thick this morning.

[Aladin Alaily] 09:07:38
Okay, so.

[Patrick Thibaudeau] 09:07:38
I just want to see what cause I tested in French.

[Patrick Thibaudeau] 09:07:42
And it's okay. It puts a lot better in English.

[Aladin Alaily] 09:07:43
Yeah. Okay, good. Good. Okay. So Mike made the entrance.

[Patrick Thibaudeau] 09:07:45
Yeah.

[Aladin Alaily] 09:07:49
When when we were going to talk, or whenever we were going to ask about the follow-up from Raoul on the batteries.

[Raul-Gabriel Edelhauser] 09:07:58
Yes, the answer is on the bottom of your screen, on the right hand side, Mr. Riley.

[Aladin Alaily] 09:08:02
Okay. Good cause. He's on the bottom of my screen, but he's not on the right hand side.

[Rob Finlayson] 09:08:07
No, he's in the center on mine. It's always in the centric of things.

[Aladin Alaily] 09:08:09
Yeah, that's correct on mine, too.

[Raul-Gabriel Edelhauser] 09:08:10
One of those guys.

[Aladin Alaily] 09:08:12
Sounds good. So do we have any updates? Did you talk to Mike Rae the like, or is he just walking right into the fire?

[Raul-Gabriel Edelhauser] 09:08:15
Yes, I did. I did, and he played the order. He plays the other I said yesterday for those 2,000 batteries that we are expecting.

[Aladin Alaily] 09:08:23
Oh, 2,000. I thought I thought it was a pick. Okay.

[Raul-Gabriel Edelhauser] 09:08:25
Yeah, we said, 1,500 to 2,000.

[Michael O'Reilly] 09:08:27
So they'll be they'll be going in today.

[Michael O'Reilly] 09:08:30
We didn't get a chance to put them in yesterday.

[Michael O'Reilly] 09:08:32
There was some other stuff going on.

[Aladin Alaily] 09:08:32
No. Problem. Okay. So for the kits, then we're gonna have 2,000 batteries, ordered Mike.

[Aladin Alaily] 09:08:40
Do we have an update on on the cables?

    [Michael O'Reilly] 09:08:44
No, no, they were supposed to ship yesterday. I asked for an update at the end of the day, but I haven't seen anything yet.

[Aladin Alaily] 09:08:49
Cables. We're supposed.

[Aladin Alaily] 09:08:54
To ship. Okay.

[Aladin Alaily] 09:09:01
Reads, communicate, and all that good stuff related to the queue card.

[Aladin Alaily] 09:09:07
Raul, and I guess no, I'm sorry I guess it's with Rob.

[Aladin Alaily] 09:09:11
Still, if I understood from yesterday.

[Rob Finlayson] 09:09:13
Yeah. Helen's taken on the producing of the collateral.

[Rob Finlayson] 09:09:18
And so we'll just need to provide that information before cuz she's working on the instruction sheets first, since we'll need that sooner to a little more head runway to get things in.

[Rob Finlayson] 09:09:29
Well, I guess when would things go in classroom? Given that classes are ongoing until the last second?

[Raul-Gabriel Edelhauser] 09:09:34
In that very last week that we got to 20 s. So.

[Rob Finlayson] 09:09:37
Okay. Alright!

[Aladin Alaily] 09:09:39
Cool, and for the team for us to actually build those kits.

[Aladin Alaily] 09:09:44
Mike, you had mentioned that you had, like at least a 100 of those absent bags.

[Michael O'Reilly] 09:09:49
Yeah, we've got a lot of bags. We'll figure something out.

[Aladin Alaily] 09:09:52
Okay. So then we'll be good.

[Michael O'Reilly] 09:09:55
Ideally, we're not producing a 100 of them, though. Right? Like.

[Michael O'Reilly] 09:09:58
I don't wanna waste all of these cables on these kids if we don't have to.

[Michael O'Reilly] 09:10:01
That'd be nice to have some stocked.

[Aladin Alaily] 09:10:04
Yeah. So let's see what the schedule looks like, because people will be picking up their kit at the beginning of the day.

[Aladin Alaily] 09:10:11
Or you know there'll be available at the beginning of the day. But I doubt that we have 100 people every single day.

[Rob Finlayson] 09:10:18
No, we don't. We're.

[Michael O'Reilly] 09:10:18
Well, it's just these kids also are not gonna come back right?

    [Michael O'Reilly] 09:10:22
We're gonna lose these cables, and they're 40 bucks each.

[Michael O'Reilly] 09:10:24
So, like, maybe.

[Aladin Alaily] 09:10:25
Yeah.

[Michael O'Reilly] 09:10:27
Maybe we just keep them in a central place or central area of that particular building, or something right?

[Aladin Alaily] 09:10:30
Yeah.

[Aladin Alaily] 09:10:33
Yeah, so yesterday, we talked about a command center where everybody's gonna be sitting signing in and picking up T-shirts.

[Aladin Alaily] 09:10:43
And what have you? So this is where we'll well looked at, having those kits where people can pick them up, or, alternatively, we also we'll have that.

[Aladin Alaily] 09:10:54
But in addition, what we talked about also is having a lead in each sort of support areas depending on where that lead is sitting.

[Rob Finlayson] 09:11:01
Yeah.

[Aladin Alaily] 09:11:03
We we could have the kids there to.

[Aladin Alaily] 09:11:06
Let's just get the kids together. And then from there we can.

[Aladin Alaily] 09:11:10
We can decide what, how best to approach that.

[Rob Finlayson] 09:11:13
Do we need any of this collateral laminated? Hey, there, for the classrooms, or for the queue for the queue cards?

[Aladin Alaily] 09:11:20
Ideally. Yes, but if it's if we cannot, then no, it's fine.

[Rob Finlayson] 09:11:21
Okay.

[Rob Finlayson] 09:11:25
I've asked Helen to take a look at at that with printing, so we that's underway.

[Aladin Alaily] 09:11:30
Okay. Yeah.

[Rob Finlayson] 09:11:32
At least the inquiries underway.

[Aladin Alaily] 09:11:34
Yeah, ideally, yes, because those will. Yeah, they won't last just like the cables.

[Rob Finlayson] 09:11:38
Yeah, they won't last. Yeah. And we can't spring background.

[Aladin Alaily] 09:11:43
Correct correct guessing is that if people take the kids out.

[Aladin Alaily] 09:11:51
And the expenses for them to return. The kits.

[Aladin Alaily] 09:11:54
They are technicians that work for us. So I'm hoping that we won't have to. Too many issues.

[Aladin Alaily] 09:12:00
But I expect that we'll lose some regardless. Okay, did we settle on the command center being in dB, 0, 0 4, pretty much.

[Rob Finlayson] 09:12:11
That's what we were talking about. I was gonna go take a look at it, find rather well, and go take a look at the room tomorrow and make sure we're good, and then I'll speak to Yasmin and the Ro to book it.

[Aladin Alaily] 09:12:21
Okay, sounds good. So from my side, those were the follow ups.

[Aladin Alaily] 09:12:28
Now I'm getting a little concerned with this whole Wi-fi and Internet connectivity.

[Rob Finlayson] 09:12:32
I wouldn't be any more concerned than anything else. I mean all of the pieces the bits and pieces are here.

[Aladin Alaily] 09:12:33
But.

[Rob Finlayson] 09:12:38
We just need somebody to do the work in the works technically being done without the Po.

[Rob Finlayson] 09:12:42
The vendor is just moving ahead. So I don't think we have any great jeopardy, but it would be great to have the eyes dotted and the teeth crossed, so we have no jeopardy.

[Aladin Alaily] 09:12:52
Yeah, agreed. And print.

[Rob Finlayson] 09:12:53
They're all working towards a test.

[Rob Finlayson] 09:12:57
So the the the end should be done. But on Friday, and then I think Tally is gonna start testing next week in situ the tent build starts on the 20 third.

[Rob Finlayson] 09:13:11
Will be finished 20, fourth, or 20, fifth, and then we'll do another test to make sure we have proper coverage of the tent itself.

[Rob Finlayson] 09:13:18
There was some talk. If we don't, of turning the boards to point the the wifi in those there, as well.

[Aladin Alaily] 09:13:27
Okay.

[Rob Finlayson] 09:13:28
At the moment, the original. The current plan, is just to use the 3 access points that are on the the 12 foot tower surrounding like right next to where the tent will be on the east side, the surrounding the pond, the reflecting pond just pointing them at the tent, and

[Aladin Alaily] 09:13:39
Okay.

[Rob Finlayson] 09:13:44
hoping that's enough. So there is backup plan in case it's not enough.

[Aladin Alaily] 09:13:46
Alright!

[Aladin Alaily] 09:13:50
Okay. And then you guys are also gonna talk about where is it now?

    [Aladin Alaily] 09:14:04
Communication, tree.

[Rob Finlayson] 09:14:07
Yes.

[Aladin Alaily] 09:14:07
The communication tree. Yeah. So you guys are, gonna start that on as well. Still, right?

    [Rob Finlayson] 09:14:10
Yeah. Yup, after the schedules done, I am gonna post A, or did I?

    [Aladin Alaily] 09:14:12
No one!

[Rob Finlayson] 09:14:18
No, I didn't. I wrote it didn't post it.

[Rob Finlayson] 09:14:19
I. I have a note to go into the team to give people an idea of things that are coming, so they don't bother us, simply do it.

[Aladin Alaily] 09:14:23
Okay, okay. I think I saw that Rob. Did you know post something about expect?

[Rob Finlayson] 09:14:29
I posted one about scheduling. I don't think I don't.

[Aladin Alaily] 09:14:32
Yeah. Oh, I see. No, no, that's the only one I saw.

[Rob Finlayson] 09:14:35
No, there's another one about what to expect with your shift and there'll be a sign in somewhere, and this and that the other thing, and there's a communication tree.

[Aladin Alaily] 09:14:37
Oh, okay.

[Rob Finlayson] 09:14:41
We'll let you know what that is, and so on.

[Aladin Alaily] 09:14:44
Okay, and in terms of printers and computers. Have you had a response from Rick on those things?

    [Aladin Alaily] 09:14:50
Or still no!

[Rob Finlayson] 09:14:50
No I have to reach out to him again today. I'm gonna just put 15 min on his schedule, and then let him know we're meeting and make sure he accepts.

[Aladin Alaily] 09:14:57
Okay, yeah, yeah, for sure. And if?

[Rob Finlayson] 09:15:01
I wanna make sure he's good and that we're not just assuming I also need to confirm with him that we have enough extension cords and power bars to meet the needs, since that's on us to provide so if we need to order something that needs to be

[Aladin Alaily] 09:15:04
Yeah.

[Aladin Alaily] 09:15:11
Yeah.

[Rob Finlayson] 09:15:13
done Asap, too. So.

[Aladin Alaily] 09:15:15
Yeah, for sure. And I am meeting with Rico on a different issue tomorrow morning.

[Aladin Alaily] 09:15:21
So, if if you have something for me to relay back to, he's the director of Canada for Rico.

                                                                                                                                                                                                                                                                   [Aladin Alaily] 09:15:30
So hold on. Let me get his real title, because maybe I can get him to do something here if we need senior solution, executive public sector.

[Aladin Alaily] 09:15:42
Rico at 9 Am.

[Rob Finlayson] 09:15:43
Well, one thing that I need from the rick is confirmation, that he has all of the printers and multifunction devices that we need.

[Aladin Alaily] 09:15:52
Okay, so so I will ping him. I will ping risk now and let him know that I'm meeting with Rico, and that you will be because he's away today.

[Aladin Alaily] 09:16:02
Hold on! Yes!

[Rob Finlayson] 09:16:02
Sorry, Rick's away today. Oh, okay. See back tomorrow.

[Aladin Alaily] 09:16:07
Yes, I know you're off, but I'm just real quick. But I'm meeting with Rico.

[Rob Finlayson] 09:16:08
Okay.

[Aladin Alaily] 09:16:20
Is there anything you want me to ask him?

    [Aladin Alaily] 09:16:29
Rep related to Congress. Okay, so we'll see what he says.

[Aladin Alaily] 09:16:35
But but yeah, from my side, guys, this is what this is what I have.

[Aladin Alaily] 09:16:47
Anything else that we need to discuss here.

[Aladin Alaily] 09:16:53
Let's go around there.

[Rob Finlayson] 09:16:54
I don't think so. I think Mike and I have a lot of things to discuss in the next couple of days.

[Rob Finlayson] 09:16:58
About zoom and accessibility and whatnot with all of the free time. But we'll we'll do that when we're in meetings together.

[Aladin Alaily] 09:17:02
Yeah.

[Aladin Alaily] 09:17:06
Sounds good sounds, good I'm meeting with Mike right after this.

[Aladin Alaily] 09:17:10
So if we finish early, which is step 4, then certainly there will be some time there.

[Aladin Alaily] 09:17:20
Hopefully, okay.

[Michael O'Reilly] 09:17:22
We have the rest of the network migration, for classrooms happening this week.

[Michael O'Reilly] 09:17:27
There's some happening today. And then Friday, we did it over the weekend as well, and then resolved issues in very hall.

[Michael O'Reilly] 09:17:33
So lot of that stuff is coming up is coming on to exile now, which is great.

[Aladin Alaily] 09:17:39
Perfect.

[Michael O'Reilly] 09:17:40
We're working on goose neck microphones right now, based on an idea that I'll thegrudgedly admit that that role suggested only because it's a good one, me, I didn't think of it first, which is to I know.

[Raul-Gabriel Edelhauser] 09:17:54
Oh, you!

[Michael O'Reilly] 09:17:55
Some of the goose. Next we have on the old tower our microphones.

[Michael O'Reilly] 09:18:00
We're gonna see if we can re purpose them. There are 4 PIN.

[Michael O'Reilly] 09:18:04
Gosh! The name for it is escaping me right now.

[Michael O'Reilly] 09:18:07
But typically used for lighting. But I'm pretty sure the only reason the third, the fourth PIN is there is for a little light on the thing, so if we can convert it to an Xlr and buy a number of sure X to use which are Xlr to USB adapters with

[Michael O'Reilly] 09:18:20
phantom power on them, we'd be able to patch them into the Pcs.

[Michael O'Reilly] 09:18:24
Which is something we're obliged to do by the terms of this agreement with the Federation.

[Aladin Alaily] 09:18:29
Well, sorry. Say that again. Whatever we're obliged to do.

[Michael O'Reilly] 09:18:32
We have to provide a audio input to the Pcs in all of our rooms, which we have a solution for.

[Aladin Alaily] 09:18:37
Oh, jeez, okay.

[Michael O'Reilly] 09:18:41
I could buy a whack of webcams for approximately the same price, but I haven't nothing that I can do with the webcams at the end of it, so I can get these in about the same timeline they're about a 100 bucks a piece.

[Aladin Alaily] 09:18:51
Okay.

[Michael O'Reilly] 09:18:52
We would need a significant, some of them probably 50.

[Michael O'Reilly] 09:18:57
So we're like $5,000 worth of USB adapters.

[Aladin Alaily] 09:19:01
Yeah. Again, on the money front. We, I think we're still in fairly good shape, like, I still need to see the schedule to see ot and all that good stuff.

[Aladin Alaily] 09:19:12
But I think that, based on the cost recovery pieces, I know entirely concerned when it comes to that just based on the estimates that we had before. So I don't want us to make our lives miserable just to save a couple of 1,000 bucks.

[Michael O'Reilly] 09:19:22
Okay.

[Aladin Alaily] 09:19:28
Yeah, I, like it. Sorry?

[Michael O'Reilly] 09:19:28
Okay, cause I can prime these and get points. I can get this on prime and get points.

[Michael O'Reilly] 09:19:36
Get my PC. Points. Man.

[Raul-Gabriel Edelhauser] 09:19:36
Yeah.

[Aladin Alaily] 09:19:38
No, no man. You gotta use my card.

[Michael O'Reilly] 09:19:41
I'm hedging my inflation here. This this is important.

[Aladin Alaily] 09:19:44
Yeah. Yeah. All good. All good. Okay. So I just wanted to point that out that you know, I think that until I see the schedule in terms of Ot, I think we're okay.

[Aladin Alaily] 09:19:56
When it comes for cost recovery. So even with the cables, the $10,000 ups.

[Aladin Alaily] 09:20:03
Actually, that's that's a another. I don't know what I let you guys know.

[Aladin Alaily] 09:20:08
Yesterday I sent a message to Steve and Brad to say, enough fooling around.

[Aladin Alaily] 09:20:14
I need to understand where this networking costs are gonna be coming from.

[Aladin Alaily] 09:20:18
And the response I got is, oh, yeah. Solomon was just inquiring.

[Aladin Alaily] 09:20:22
But we're gonna pay for all that through the infrastructure budget.

[Aladin Alaily] 09:20:26
So that $10,000 for cables or ups, or whatever the heck that was taxes in that's coming out of their 700 fund for infrastructure.

[Aladin Alaily] 09:20:36
So that is not something that is going to be tacked on to our well, I say, but to the Congress Budget.

[Rob Finlayson] 09:20:45
Yeah. Most of that was, for you know, Cling, and the labor related to that.

[Aladin Alaily] 09:20:49
Yeah.

[Rob Finlayson] 09:20:51
I mean, I will put ends on cables for a two-thirds that cost myself.

[Aladin Alaily] 09:20:58
The the last point I wanna make about scheduling is as good folks do.

[Rob Finlayson] 09:20:58
Yes.

[Aladin Alaily] 09:21:06
We have sessions at Austin happening at Austin.

[Raul-Gabriel Edelhauser] 09:21:08
Nope!

[Michael O'Reilly] 09:21:09
Badly.

[Raul-Gabriel Edelhauser] 09:21:11
For session for the Congress. Yes.

[Rob Finlayson] 09:21:12
Yes.

[Aladin Alaily] 09:21:13
Oh, how many!

[Rob Finlayson] 09:21:16
Oh!

[Aladin Alaily] 09:21:16
Like off the top of your head like not not precise like.

[Aladin Alaily] 09:21:20
Is it more than 10?

    [Rob Finlayson] 09:21:23
There's only there I have it in front of me here.

[Aladin Alaily] 09:21:26
No like total.

[Rob Finlayson] 09:21:30
So total events, sessions, too, for.

[Rob Finlayson] 09:21:35
Oh, I don't know. I just on average 5 sessions per day.

[Rob Finlayson] 09:21:40
So 30, 35.

[Raul-Gabriel Edelhauser] 09:21:41
35. Yeah.

[Aladin Alaily] 09:21:41
At all. It's good. Okay? Okay, that's fine. That's fine.

[Aladin Alaily] 09:21:45
Okay. Alright, that's all I wanted to know.

[Rob Finlayson] 09:21:49
Yeah, we're gonna try and assign people that are familiar with the buildings to the buildings.

[Aladin Alaily] 09:21:55
Yeah.

[Rob Finlayson] 09:21:55
And for version, on and to like. Specifically, I'll be speaking to like Jeff and Shawn and Whatnot about that, asking them what what they need to supplement it.

[Aladin Alaily] 09:21:58
Yeah.

[Aladin Alaily] 09:22:03
But we need to make. I need to.

[Aladin Alaily] 09:22:08
Yeah, that's for sure. And for sure.

[Aladin Alaily] 09:22:12
But I need to make something very clear. But you guys probably already know.

[Aladin Alaily] 09:22:16
But maybe this is from my own benefit of hearing myself say this. But Osgood has no idea how their rooms work, so we shouldn't right, so we shouldn't expect them to know anything about anything.

[Raul-Gabriel Edelhauser] 09:22:23
Never did.

[Rob Finlayson] 09:22:24
Well, if if we put them in their rooms, though maybe they'll come up with a little more knowledge at the end of Congress, and that will be a long term benefit of Congress, too.

[Aladin Alaily] 09:22:30
There'll be that comfort level.

[Aladin Alaily] 09:22:37
Yeah, that's right.

[Michael O'Reilly] 09:22:37
I would not ever count on that.

[Aladin Alaily] 09:22:40
Yeah. So the reason? So?

[Rob Finlayson] 09:22:40
Not counting on it, but you know the probability is just above 0.

[Michael O'Reilly] 09:22:41
Okay.

[Michael O'Reilly] 09:22:45
Yeah, 50.

[Rob Finlayson] 09:22:45
Right? So it's in the right direction.

[Aladin Alaily] 09:22:47
So I'm I'm gonna fill you guys in on why, I'm asking this question.

[Patrick Thibaudeau] 09:22:48
Hmm!

[Aladin Alaily] 09:22:52
I thought first time. Maybe I won't, because it doesn't really add any value, but I think it does add value in terms of adding some validity to what you're saying is that yesterday Brent at Osgood made it very clear that as good tech team was doing U it a favor

[Patrick Thibaudeau] 09:23:12
Oh, God!

[Aladin Alaily] 09:23:13
by giving their time to do support, and I almost told them, you know what we don't need.

[Aladin Alaily] 09:23:21
Your people. We don't need them.

[Michael O'Reilly] 09:23:23
We actually desperately do not. I'd really much rather not deal with.

[Aladin Alaily] 09:23:26
So don't schedule them.

[Michael O'Reilly] 09:23:27
I'd really rather not have Shorora and Mark.

[Rob Finlayson] 09:23:29
If you don't want me to schedule them, I won't, because there's only 2 that signed up Mark signed up for a good number of ships, and Shorter signed up at a time that does not work for us he just said here's when I'm available

[Raul-Gabriel Edelhauser] 09:23:34
Oh, yeah.

[Raul-Gabriel Edelhauser] 09:23:36
We are here.

[Rob Finlayson] 09:23:44
that had nothing to do with when we needed people. And so, whatever.

[Rob Finlayson] 09:23:48
And he's the one that was completing on the team.

[Aladin Alaily] 09:23:50
Correct.

[Rob Finlayson] 09:23:50
By the way, that he needs all the written instructions because he can't go to training.

[Aladin Alaily] 09:23:53
Correct.

[Rob Finlayson] 09:23:53
So we'll make sure that it's not required for him to be a training.

[Aladin Alaily] 09:23:57
Correct.

[Michael O'Reilly] 09:23:57
Well, they don't need to. I could say this here. I don't care. Those who are useless.

[Aladin Alaily] 09:24:02
Yeah, yeah, yeah.

[Michael O'Reilly] 09:24:02
They do absolute, Jack, all, and there's no way that any of the events that we're having in Osgood, like 1014 that they're going to be able to serve any useful purpose.

[Michael O'Reilly] 09:24:09
Besides sitting there and complaining. So I'd really just rather not see them or hear them at all, for the entire they go take a vacation for the week, volunteer for that.

[Aladin Alaily] 09:24:14
Bingo!

[Michael O'Reilly] 09:24:17
So, yeah.

[Aladin Alaily] 09:24:17
Bingo. So so this is why I was asking why I was asking so if there's an opportunity.

[Rob Finlayson] 09:24:22
So I could schedule a mark, and for some afternoons when we don't have anything over there, we just have things ending.

[Michael O'Reilly] 09:24:28
Great he can he hold the door like, yeah.

[Aladin Alaily] 09:24:28
What? Yeah. Remember the but I don't want to pay.

[Aladin Alaily] 09:24:34
Sorry, but I don't want to pay him overtime.

[Rob Finlayson] 09:24:37
I'm sorry.

[Aladin Alaily] 09:24:38
I do. I do not wanna pay him overtime just to sit there.

[Aladin Alaily] 09:24:42
He is not an insurance policy that I wanna pay for, because remember what I said yesterday, right?

    [Rob Finlayson] 09:24:44
Yeah.

[Rob Finlayson] 09:24:50
Too many clauses and exceptions. Hmm!

[Aladin Alaily] 09:24:51
That, like, we have the insurance policy. No, but all drugs aside.

[Aladin Alaily] 09:24:56
If we need them, we need them. But I'm just saying that they made a a Brent, made it abundantly clear that as good is is helping uit, not university, but uit by donating the time.

[Aladin Alaily] 09:25:11
And it was a very, very difficult thing to convince the staff to do so, because they don't feel as though you, it ever gives them anything so just.

[Patrick Thibaudeau] 09:25:19
Oh! Give me a break!

[Aladin Alaily] 09:25:21
I'm telling you. I am telling you. Yesterday yesterday I was like, you know what? That's actually Patrick.

[Raul-Gabriel Edelhauser] 09:25:22
Voice, of reason.

[Aladin Alaily] 09:25:27
That's the reason why I missed that cause. I had a meeting with Brent about about this stuff. But, anyhow.

[Patrick Thibaudeau] 09:25:34
Good miss much. By the way.

[Aladin Alaily] 09:25:36
Yeah, I heard, Marie went off a little bit on Congress communication, apparently, but whatever it's fine.

[Patrick Thibaudeau] 09:25:40
Yeah.

[Patrick Thibaudeau] 09:25:42
Yeah, whatever.

[Aladin Alaily] 09:25:44
Oh, I I can chat with her, if need be.

[Aladin Alaily] 09:25:47
Alright! So with that tidbit of fun, news, and sunshine, I will bid you a good day, and please do reach out to Patrick, and I, because we may need a break from this Microsoft.

[Aladin Alaily] 09:26:00
What 6 h meeting that we have. So any interruptions are welcome.

[Rob Finlayson] 09:26:06
Oh, do enjoy it!

[Aladin Alaily] 09:26:09
Oh, my gosh! I'm having too much fun already!
[Rob Finlayson] 09:07:07
Share it and keep working. But I won't be sending any confirmation to anybody until we're in agreement that we're on the right path.

[Aladin Alaily] 09:07:09
Okay.

[Aladin Alaily] 09:07:16
Sounds good sounds good, so is my accent a little too sick here, cause it looks like somebody set the closed captioning.

[Patrick Thibaudeau] 09:07:23
I did. I'm just testing it. No, I'm just testing it to see how it's worked.

[Aladin Alaily] 09:07:25
So, yeah, okay, that was funny. I saw close captioning.

[Patrick Thibaudeau] 09:07:29
So!

[Aladin Alaily] 09:07:31
I'm like, damn my, my accent would be really thick this morning.

[Aladin Alaily] 09:07:38
Okay, so.

[Patrick Thibaudeau] 09:07:38
I just want to see what cause I tested in French.

[Patrick Thibaudeau] 09:07:42
And it's okay. It puts a lot better in English.

[Aladin Alaily] 09:07:43
Yeah. Okay, good. Good. Okay. So Mike made the entrance.

[Patrick Thibaudeau] 09:07:45
Yeah.

[Aladin Alaily] 09:07:49
When when we were going to talk, or whenever we were going to ask about the follow-up from Raoul on the batteries.

[Raul-Gabriel Edelhauser] 09:07:58
Yes, the answer is on the bottom of your screen, on the right hand side, Mr. Riley.

[Aladin Alaily] 09:08:02
Okay. Good cause. He's on the bottom of my screen, but he's not on the right hand side.

[Rob Finlayson] 09:08:07
No, he's in the center on mine. It's always in the centric of things.

[Aladin Alaily] 09:08:09
Yeah, that's correct on mine, too.

[Raul-Gabriel Edelhauser] 09:08:10
One of those guys.

[Aladin Alaily] 09:08:12
Sounds good. So do we have any updates? Did you talk to Mike Rae the like, or is he just walking right into the fire?

    [Raul-Gabriel Edelhauser] 09:08:15
Yes, I did. I did, and he played the order. He plays the other I said yesterday for those 2,000 batteries that we are expecting.

[Aladin Alaily] 09:08:23
Oh, 2,000. I thought I thought it was a pick. Okay.

[Raul-Gabriel Edelhauser] 09:08:25
Yeah, we said, 1,500 to 2,000.

[Michael O'Reilly] 09:08:27
So they'll be they'll be going in today.

[Michael O'Reilly] 09:08:30
We didn't get a chance to put them in yesterday.

[Michael O'Reilly] 09:08:32
There was some other stuff going on.

[Aladin Alaily] 09:08:32
No. Problem. Okay. So for the kits, then we're gonna have 2,000 batteries, ordered Mike.

[Aladin Alaily] 09:08:40
Do we have an update on on the cables?

[Michael O'Reilly] 09:08:44
No, no, they were supposed to ship yesterday. I asked for an update at the end of the day, but I haven't seen anything yet.

[Aladin Alaily] 09:08:49
Cables. We're supposed.

[Aladin Alaily] 09:08:54
To ship. Okay.

[Aladin Alaily] 09:09:01
Reads, communicate, and all that good stuff related to the queue card.

[Aladin Alaily] 09:09:07
Raul, and I guess no, I'm sorry I guess it's with Rob.

[Aladin Alaily] 09:09:11
Still, if I understood from yesterday.

[Rob Finlayson] 09:09:13
Yeah. Helen's taken on the producing of the collateral.

[Rob Finlayson] 09:09:18
And so we'll just need to provide that information before cuz she's working on the instruction sheets first, since we'll need that sooner to a little more head runway to get things in.

[Rob Finlayson] 09:09:29
Well, I guess when would things go in classroom? Given that classes are ongoing until the last second?

    [Raul-Gabriel Edelhauser] 09:09:34
In that very last week that we got to 20 s. So.

[Rob Finlayson] 09:09:37
Okay. Alright!

[Aladin Alaily] 09:09:39
Cool, and for the team for us to actually build those kits.

[Aladin Alaily] 09:09:44
Mike, you had mentioned that you had, like at least a 100 of those absent bags.

[Michael O'Reilly] 09:09:49
Yeah, we've got a lot of bags. We'll figure something out.

[Aladin Alaily] 09:09:52
Okay. So then we'll be good.

[Michael O'Reilly] 09:09:55
Ideally, we're not producing a 100 of them, though. Right? Like.

    [Michael O'Reilly] 09:09:58
I don't wanna waste all of these cables on these kids if we don't have to.

[Michael O'Reilly] 09:10:01
That'd be nice to have some stocked.

[Aladin Alaily] 09:10:04
Yeah. So let's see what the schedule looks like, because people will be picking up their kit at the beginning of the day.

[Aladin Alaily] 09:10:11
Or you know there'll be available at the beginning of the day. But I doubt that we have 100 people every single day.

[Rob Finlayson] 09:10:18
No, we don't. We're.

[Michael O'Reilly] 09:10:18
Well, it's just these kids also are not gonna come back right?

[Michael O'Reilly] 09:10:22
We're gonna lose these cables, and they're 40 bucks each.

[Michael O'Reilly] 09:10:24
So, like, maybe.

[Aladin Alaily] 09:10:25
Yeah.

[Michael O'Reilly] 09:10:27
Maybe we just keep them in a central place or central area of that particular building, or something right?

    [Aladin Alaily] 09:10:30
Yeah.

[Aladin Alaily] 09:10:33
Yeah, so yesterday, we talked about a command center where everybody's gonna be sitting signing in and picking up T-shirts.

[Aladin Alaily] 09:10:43
And what have you? So this is where we'll well looked at, having those kits where people can pick them up, or, alternatively, we also we'll have that.

[Aladin Alaily] 09:10:54
But in addition, what we talked about also is having a lead in each sort of support areas depending on where that lead is sitting.

[Rob Finlayson] 09:11:01
Yeah.

[Aladin Alaily] 09:11:03
We we could have the kids there to.

[Aladin Alaily] 09:11:06
Let's just get the kids together. And then from there we can.

[Aladin Alaily] 09:11:10
We can decide what, how best to approach that.

[Rob Finlayson] 09:11:13
Do we need any of this collateral laminated? Hey, there, for the classrooms, or for the queue for the queue cards?

    [Aladin Alaily] 09:11:20
Ideally. Yes, but if it's if we cannot, then no, it's fine.

[Rob Finlayson] 09:11:21
Okay.

[Rob Finlayson] 09:11:25
I've asked Helen to take a look at at that with printing, so we that's underway.

[Aladin Alaily] 09:11:30
Okay. Yeah.

[Rob Finlayson] 09:11:32
At least the inquiries underway.

[Aladin Alaily] 09:11:34
Yeah, ideally, yes, because those will. Yeah, they won't last just like the cables.

[Rob Finlayson] 09:11:38
Yeah, they won't last. Yeah. And we can't spring background.

[Aladin Alaily] 09:11:43
Correct correct guessing is that if people take the kids out.

[Aladin Alaily] 09:11:51
And the expenses for them to return. The kits.

[Aladin Alaily] 09:11:54
They are technicians that work for us. So I'm hoping that we won't have to. Too many issues.

[Aladin Alaily] 09:12:00
But I expect that we'll lose some regardless. Okay, did we settle on the command center being in dB, 0, 0 4, pretty much.

[Rob Finlayson] 09:12:11
That's what we were talking about. I was gonna go take a look at it, find rather well, and go take a look at the room tomorrow and make sure we're good, and then I'll speak to Yasmin and the Ro to book it.

[Aladin Alaily] 09:12:21
Okay, sounds good. So from my side, those were the follow ups.

[Aladin Alaily] 09:12:28
Now I'm getting a little concerned with this whole Wi-fi and Internet connectivity.

[Rob Finlayson] 09:12:32
I wouldn't be any more concerned than anything else. I mean all of the pieces the bits and pieces are here.

[Aladin Alaily] 09:12:33
But.

[Rob Finlayson] 09:12:38
We just need somebody to do the work in the works technically being done without the Po.

[Rob Finlayson] 09:12:42
The vendor is just moving ahead. So I don't think we have any great jeopardy, but it would be great to have the eyes dotted and the teeth crossed, so we have no jeopardy.

[Aladin Alaily] 09:12:52
Yeah, agreed. And print.

[Rob Finlayson] 09:12:53
They're all working towards a test.

[Rob Finlayson] 09:12:57
So the the the end should be done. But on Friday, and then I think Tally is gonna start testing next week in situ the tent build starts on the 20 third.

[Rob Finlayson] 09:13:11
Will be finished 20, fourth, or 20, fifth, and then we'll do another test to make sure we have proper coverage of the tent itself.

[Rob Finlayson] 09:13:18
There was some talk. If we don't, of turning the boards to point the the wifi in those there, as well.

[Aladin Alaily] 09:13:27
Okay.

[Rob Finlayson] 09:13:28
At the moment, the original. The current plan, is just to use the 3 access points that are on the the 12 foot tower surrounding like right next to where the tent will be on the east side, the surrounding the pond, the reflecting pond just pointing them at the tent, and

[Aladin Alaily] 09:13:39
Okay.

[Rob Finlayson] 09:13:44
hoping that's enough. So there is backup plan in case it's not enough.

[Aladin Alaily] 09:13:46
Alright!

[Aladin Alaily] 09:13:50
Okay. And then you guys are also gonna talk about where is it now?

[Aladin Alaily] 09:14:04
Communication, tree.

[Rob Finlayson] 09:14:07
Yes.

[Aladin Alaily] 09:14:07
The communication tree. Yeah. So you guys are, gonna start that on as well. Still, right?

[Rob Finlayson] 09:14:10
Yeah. Yup, after the schedules done, I am gonna post A, or did I?

[Aladin Alaily] 09:14:12
No one!

[Rob Finlayson] 09:14:18
No, I didn't. I wrote it didn't post it.

[Rob Finlayson] 09:14:19
I. I have a note to go into the team to give people an idea of things that are coming, so they don't bother us, simply do it.

[Aladin Alaily] 09:14:23
Okay, okay. I think I saw that Rob. Did you know post something about expect?

    [Rob Finlayson] 09:14:29
I posted one about scheduling. I don't think I don't.

[Aladin Alaily] 09:14:32
Yeah. Oh, I see. No, no, that's the only one I saw.

[Rob Finlayson] 09:14:35
No, there's another one about what to expect with your shift and there'll be a sign in somewhere, and this and that the other thing, and there's a communication tree.

[Aladin Alaily] 09:14:37
Oh, okay.

[Rob Finlayson] 09:14:41
We'll let you know what that is, and so on.

[Aladin Alaily] 09:14:44
Okay, and in terms of printers and computers. Have you had a response from Rick on those things?

[Aladin Alaily] 09:14:50
Or still no!

[Rob Finlayson] 09:14:50
No I have to reach out to him again today. I'm gonna just put 15 min on his schedule, and then let him know we're meeting and make sure he accepts.

[Aladin Alaily] 09:14:57
Okay, yeah, yeah, for sure. And if?

[Rob Finlayson] 09:15:01
I wanna make sure he's good and that we're not just assuming I also need to confirm with him that we have enough extension cords and power bars to meet the needs, since that's on us to provide so if we need to order something that needs to be

[Aladin Alaily] 09:15:04
Yeah.

[Aladin Alaily] 09:15:11
Yeah.

[Rob Finlayson] 09:15:13
done Asap, too. So.

[Aladin Alaily] 09:15:15
Yeah, for sure. And I am meeting with Rico on a different issue tomorrow morning.

[Aladin Alaily] 09:15:21
So, if if you have something for me to relay back to, he's the director of Canada for Rico.

[Aladin Alaily] 09:15:30
So hold on. Let me get his real title, because maybe I can get him to do something here if we need senior solution, executive public sector.

[Aladin Alaily] 09:15:42
Rico at 9 Am.

[Rob Finlayson] 09:15:43
Well, one thing that I need from the rick is confirmation, that he has all of the printers and multifunction devices that we need.

[Aladin Alaily] 09:15:52
Okay, so so I will ping him. I will ping risk now and let him know that I'm meeting with Rico, and that you will be because he's away today.

[Aladin Alaily] 09:16:02
Hold on! Yes!

[Rob Finlayson] 09:16:02
Sorry, Rick's away today. Oh, okay. See back tomorrow.

[Aladin Alaily] 09:16:07
Yes, I know you're off, but I'm just real quick. But I'm meeting with Rico.

[Rob Finlayson] 09:16:08
Okay.

[Aladin Alaily] 09:16:20
Is there anything you want me to ask him?

[Aladin Alaily] 09:16:29
Rep related to Congress. Okay, so we'll see what he says.

[Aladin Alaily] 09:16:35
But but yeah, from my side, guys, this is what this is what I have.

[Aladin Alaily] 09:16:47
Anything else that we need to discuss here.

[Aladin Alaily] 09:16:53
Let's go around there.

[Rob Finlayson] 09:16:54
I don't think so. I think Mike and I have a lot of things to discuss in the next couple of days.

[Rob Finlayson] 09:16:58
About zoom and accessibility and whatnot with all of the free time. But we'll we'll do that when we're in meetings together.

[Aladin Alaily] 09:17:02
Yeah.

[Aladin Alaily] 09:17:06
Sounds good sounds, good I'm meeting with Mike right after this.

[Aladin Alaily] 09:17:10
So if we finish early, which is step 4, then certainly there will be some time there.

[Aladin Alaily] 09:17:20
Hopefully, okay.

[Michael O'Reilly] 09:17:22
We have the rest of the network migration, for classrooms happening this week.

[Michael O'Reilly] 09:17:27
There's some happening today. And then Friday, we did it over the weekend as well, and then resolved issues in very hall.

[Michael O'Reilly] 09:17:33
So lot of that stuff is coming up is coming on to exile now, which is great.

[Aladin Alaily] 09:17:39
Perfect.

[Michael O'Reilly] 09:17:40
We're working on goose neck microphones right now, based on an idea that I'll thegrudgedly admit that that role suggested only because it's a good one, me, I didn't think of it first, which is to I know.

[Raul-Gabriel Edelhauser] 09:17:54
Oh, you!

[Michael O'Reilly] 09:17:55
Some of the goose. Next we have on the old tower our microphones.

[Michael O'Reilly] 09:18:00
We're gonna see if we can re purpose them. There are 4 PIN.

[Michael O'Reilly] 09:18:04
Gosh! The name for it is escaping me right now.

[Michael O'Reilly] 09:18:07
But typically used for lighting. But I'm pretty sure the only reason the third, the fourth PIN is there is for a little light on the thing, so if we can convert it to an Xlr and buy a number of sure X to use which are Xlr to USB adapters with

[Michael O'Reilly] 09:18:20
phantom power on them, we'd be able to patch them into the Pcs.

[Michael O'Reilly] 09:18:24
Which is something we're obliged to do by the terms of this agreement with the Federation.

[Aladin Alaily] 09:18:29
Well, sorry. Say that again. Whatever we're obliged to do.

[Michael O'Reilly] 09:18:32
We have to provide a audio input to the Pcs in all of our rooms, which we have a solution for.

[Aladin Alaily] 09:18:37
Oh, jeez, okay.

[Michael O'Reilly] 09:18:41
I could buy a whack of webcams for approximately the same price, but I haven't nothing that I can do with the webcams at the end of it, so I can get these in about the same timeline they're about a 100 bucks a piece.

[Aladin Alaily] 09:18:51
Okay.

[Michael O'Reilly] 09:18:52
We would need a significant, some of them probably 50.

[Michael O'Reilly] 09:18:57
So we're like $5,000 worth of USB adapters.

[Aladin Alaily] 09:19:01
Yeah. Again, on the money front. We, I think we're still in fairly good shape, like, I still need to see the schedule to see ot and all that good stuff.

[Aladin Alaily] 09:19:12
But I think that, based on the cost recovery pieces, I know entirely concerned when it comes to that just based on the estimates that we had before. So I don't want us to make our lives miserable just to save a couple of 1,000 bucks.

[Michael O'Reilly] 09:19:22
Okay.

[Aladin Alaily] 09:19:28
Yeah, I, like it. Sorry?

    [Michael O'Reilly] 09:19:28
Okay, cause I can prime these and get points. I can get this on prime and get points.

[Michael O'Reilly] 09:19:36
Get my PC. Points. Man.

[Raul-Gabriel Edelhauser] 09:19:36
Yeah.

[Aladin Alaily] 09:19:38
No, no man. You gotta use my card.

[Michael O'Reilly] 09:19:41
I'm hedging my inflation here. This this is important.

[Aladin Alaily] 09:19:44
Yeah. Yeah. All good. All good. Okay. So I just wanted to point that out that you know, I think that until I see the schedule in terms of Ot, I think we're okay.

[Aladin Alaily] 09:19:56
When it comes for cost recovery. So even with the cables, the $10,000 ups.

[Aladin Alaily] 09:20:03
Actually, that's that's a another. I don't know what I let you guys know.

[Aladin Alaily] 09:20:08
Yesterday I sent a message to Steve and Brad to say, enough fooling around.

[Aladin Alaily] 09:20:14
I need to understand where this networking costs are gonna be coming from.

[Aladin Alaily] 09:20:18
And the response I got is, oh, yeah. Solomon was just inquiring.

[Aladin Alaily] 09:20:22
But we're gonna pay for all that through the infrastructure budget.

[Aladin Alaily] 09:20:26
So that $10,000 for cables or ups, or whatever the heck that was taxes in that's coming out of their 700 fund for infrastructure.

                                                                                                                        [Aladin Alaily] 09:20:36
So that is not something that is going to be tacked on to our well, I say, but to the Congress Budget.

[Rob Finlayson] 09:20:45
Yeah. Most of that was, for you know, Cling, and the labor related to that.

[Aladin Alaily] 09:20:49
Yeah.

[Rob Finlayson] 09:20:51
I mean, I will put ends on cables for a two-thirds that cost myself.

[Aladin Alaily] 09:20:58
The the last point I wanna make about scheduling is as good folks do.

[Rob Finlayson] 09:20:58
Yes.

[Aladin Alaily] 09:21:06
We have sessions at Austin happening at Austin.

[Raul-Gabriel Edelhauser] 09:21:08
Nope!

[Michael O'Reilly] 09:21:09
Badly.

[Raul-Gabriel Edelhauser] 09:21:11
For session for the Congress. Yes.

[Rob Finlayson] 09:21:12
Yes.

[Aladin Alaily] 09:21:13
Oh, how many!

[Rob Finlayson] 09:21:16
Oh!

[Aladin Alaily] 09:21:16
Like off the top of your head like not not precise like.

[Aladin Alaily] 09:21:20
Is it more than 10?

[Rob Finlayson] 09:21:23
There's only there I have it in front of me here.

[Aladin Alaily] 09:21:26
No like total.

[Rob Finlayson] 09:21:30
So total events, sessions, too, for.

[Rob Finlayson] 09:21:35
Oh, I don't know. I just on average 5 sessions per day.

[Rob Finlayson] 09:21:40
So 30, 35.

[Raul-Gabriel Edelhauser] 09:21:41
35. Yeah.

[Aladin Alaily] 09:21:41
At all. It's good. Okay? Okay, that's fine. That's fine.

[Aladin Alaily] 09:21:45
Okay. Alright, that's all I wanted to know.

[Rob Finlayson] 09:21:49
Yeah, we're gonna try and assign people that are familiar with the buildings to the buildings.

[Aladin Alaily] 09:21:55
Yeah.

[Rob Finlayson] 09:21:55
And for version, on and to like. Specifically, I'll be speaking to like Jeff and Shawn and Whatnot about that, asking them what what they need to supplement it.

[Aladin Alaily] 09:21:58
Yeah.

[Aladin Alaily] 09:22:03
But we need to make. I need to.

[Aladin Alaily] 09:22:08
Yeah, that's for sure. And for sure.

                                                                               [Aladin Alaily] 09:22:12
But I need to make something very clear. But you guys probably already know.

[Aladin Alaily] 09:22:16
But maybe this is from my own benefit of hearing myself say this. But Osgood has no idea how their rooms work, so we shouldn't right, so we shouldn't expect them to know anything about anything.

[Raul-Gabriel Edelhauser] 09:22:23
Never did.

[Rob Finlayson] 09:22:24
Well, if if we put them in their rooms, though maybe they'll come up with a little more knowledge at the end of Congress, and that will be a long term benefit of Congress, too.

[Aladin Alaily] 09:22:30
There'll be that comfort level.

[Aladin Alaily] 09:22:37
Yeah, that's right.

[Michael O'Reilly] 09:22:37
I would not ever count on that.

[Aladin Alaily] 09:22:40
Yeah. So the reason? So?

    [Rob Finlayson] 09:22:40
Not counting on it, but you know the probability is just above 0.

[Michael O'Reilly] 09:22:41
Okay.

[Michael O'Reilly] 09:22:45
Yeah, 50.

[Rob Finlayson] 09:22:45
Right? So it's in the right direction.

[Aladin Alaily] 09:22:47
So I'm I'm gonna fill you guys in on why, I'm asking this question.

[Patrick Thibaudeau] 09:22:48
Hmm!

[Aladin Alaily] 09:22:52
I thought first time. Maybe I won't, because it doesn't really add any value, but I think it does add value in terms of adding some validity to what you're saying is that yesterday Brent at Osgood made it very clear that as good tech team was doing U it a favor

[Patrick Thibaudeau] 09:23:12
Oh, God!

[Aladin Alaily] 09:23:13
by giving their time to do support, and I almost told them, you know what we don't need.

[Aladin Alaily] 09:23:21
Your people. We don't need them.

[Michael O'Reilly] 09:23:23
We actually desperately do not. I'd really much rather not deal with.

[Aladin Alaily] 09:23:26
So don't schedule them.

[Michael O'Reilly] 09:23:27
I'd really rather not have Shorora and Mark.

[Rob Finlayson] 09:23:29
If you don't want me to schedule them, I won't, because there's only 2 that signed up Mark signed up for a good number of ships, and Shorter signed up at a time that does not work for us he just said here's when I'm available

[Raul-Gabriel Edelhauser] 09:23:34
Oh, yeah.

[Raul-Gabriel Edelhauser] 09:23:36
We are here.

[Rob Finlayson] 09:23:44
that had nothing to do with when we needed people. And so, whatever.

[Rob Finlayson] 09:23:48
And he's the one that was completing on the team.

[Aladin Alaily] 09:23:50
Correct.

[Rob Finlayson] 09:23:50
By the way, that he needs all the written instructions because he can't go to training.

[Aladin Alaily] 09:23:53
Correct.

[Rob Finlayson] 09:23:53
So we'll make sure that it's not required for him to be a training.

[Aladin Alaily] 09:23:57
Correct.

[Michael O'Reilly] 09:23:57
Well, they don't need to. I could say this here. I don't care. Those who are useless.

[Aladin Alaily] 09:24:02
Yeah, yeah, yeah.

[Michael O'Reilly] 09:24:02
They do absolute, Jack, all, and there's no way that any of the events that we're having in Osgood, like 1014 that they're going to be able to serve any useful purpose.

[Michael O'Reilly] 09:24:09
Besides sitting there and complaining. So I'd really just rather not see them or hear them at all, for the entire they go take a vacation for the week, volunteer for that.

                                                                                                                                                                                [Aladin Alaily] 09:24:14
Bingo!

[Michael O'Reilly] 09:24:17
So, yeah.

[Aladin Alaily] 09:24:17
Bingo. So so this is why I was asking why I was asking so if there's an opportunity.

[Rob Finlayson] 09:24:22
So I could schedule a mark, and for some afternoons when we don't have anything over there, we just have things ending.

[Michael O'Reilly] 09:24:28
Great he can he hold the door like, yeah.

[Aladin Alaily] 09:24:28
What? Yeah. Remember the but I don't want to pay.

[Aladin Alaily] 09:24:34
Sorry, but I don't want to pay him overtime.

[Rob Finlayson] 09:24:37
I'm sorry.

[Aladin Alaily] 09:24:38
I do. I do not wanna pay him overtime just to sit there.

[Aladin Alaily] 09:24:42
He is not an insurance policy that I wanna pay for, because remember what I said yesterday, right?

[Rob Finlayson] 09:24:44
Yeah.

[Rob Finlayson] 09:24:50
Too many clauses and exceptions. Hmm!

[Aladin Alaily] 09:24:51
That, like, we have the insurance policy. No, but all drugs aside.

[Aladin Alaily] 09:24:56
If we need them, we need them. But I'm just saying that they made a a Brent, made it abundantly clear that as good is is helping uit, not university, but uit by donating the time.

[Aladin Alaily] 09:25:11
And it was a very, very difficult thing to convince the staff to do so, because they don't feel as though you, it ever gives them anything so just.

[Patrick Thibaudeau] 09:25:19
Oh! Give me a break!

[Aladin Alaily] 09:25:21
I'm telling you. I am telling you. Yesterday yesterday I was like, you know what? That's actually Patrick.

[Raul-Gabriel Edelhauser] 09:25:22
Voice, of reason.

[Aladin Alaily] 09:25:27
That's the reason why I missed that cause. I had a meeting with Brent about about this stuff. But, anyhow.

[Patrick Thibaudeau] 09:25:34
Good miss much. By the way.

[Aladin Alaily] 09:25:36
Yeah, I heard, Marie went off a little bit on Congress communication, apparently, but whatever it's fine.

[Patrick Thibaudeau] 09:25:40
Yeah.

[Patrick Thibaudeau] 09:25:42
Yeah, whatever.

[Aladin Alaily] 09:25:44
Oh, I I can chat with her, if need be.

[Aladin Alaily] 09:25:47
Alright! So with that tidbit of fun, news, and sunshine, I will bid you a good day, and please do reach out to Patrick, and I, because we may need a break from this Microsoft.

[Aladin Alaily] 09:26:00
What 6 h meeting that we have. So any interruptions are welcome.

[Rob Finlayson] 09:26:06
Oh, do enjoy it!

[Aladin Alaily] 09:26:09
Oh, my gosh! I'm having too much fun already!

[Aladin Alaily] 09:26:14
Okay. So, Rob, are you good?

    [Rob Finlayson] 09:26:16
Yup, I'm good.

[Aladin Alaily] 09:26:17
Okay. Patrick, on your side.



[Aladin Alaily] 09:26:14
Okay. So, Rob, are you good?

[Rob Finlayson] 09:26:16
Yup, I'm good.

[Aladin Alaily] 09:26:17
Okay. Patrick, on your side.




[Aladin Alaily] 09:07:16
Sounds good sounds good, so is my accent a little too sick here, cause it looks like somebody set the closed captioning.

[Patrick Thibaudeau] 09:07:23
I did. I'm just testing it. No, I'm just testing it to see how it's worked.

[Aladin Alaily] 09:07:25
So, yeah, okay, that was funny. I saw close captioning.

[Patrick Thibaudeau] 09:07:29
So!

[Aladin Alaily] 09:07:31
I'm like, damn my, my accent would be really thick this morning.

[Aladin Alaily] 09:07:38
Okay, so.

[Patrick Thibaudeau] 09:07:38
I just want to see what cause I tested in French.

[Patrick Thibaudeau] 09:07:42
And it's okay. It puts a lot better in English.

[Aladin Alaily] 09:07:43
Yeah. Okay, good. Good. Okay. So Mike made the entrance.

[Patrick Thibaudeau] 09:07:45
Yeah.

[Aladin Alaily] 09:07:49
When when we were going to talk, or whenever we were going to ask about the follow-up from Raoul on the batteries.

[Raul-Gabriel Edelhauser] 09:07:58
Yes, the answer is on the bottom of your screen, on the right hand side, Mr. Riley.

[Aladin Alaily] 09:08:02
Okay. Good cause. He's on the bottom of my screen, but he's not on the right hand side.

[Rob Finlayson] 09:08:07
No, he's in the center on mine. It's always in the centric of things.

[Aladin Alaily] 09:08:09
Yeah, that's correct on mine, too.

[Raul-Gabriel Edelhauser] 09:08:10
One of those guys.

[Aladin Alaily] 09:08:12
Sounds good. So do we have any updates? Did you talk to Mike Rae the like, or is he just walking right into the fire?

    [Raul-Gabriel Edelhauser] 09:08:15
Yes, I did. I did, and he played the order. He plays the other I said yesterday for those 2,000 batteries that we are expecting.

[Aladin Alaily] 09:08:23
Oh, 2,000. I thought I thought it was a pick. Okay.

[Raul-Gabriel Edelhauser] 09:08:25
Yeah, we said, 1,500 to 2,000.

[Michael O'Reilly] 09:08:27
So they'll be they'll be going in today.

[Michael O'Reilly] 09:08:30
We didn't get a chance to put them in yesterday.

[Michael O'Reilly] 09:08:32
There was some other stuff going on.

[Aladin Alaily] 09:08:32
No. Problem. Okay. So for the kits, then we're gonna have 2,000 batteries, ordered Mike.

[Aladin Alaily] 09:08:40
Do we have an update on on the cables?

[Michael O'Reilly] 09:08:44
No, no, they were supposed to ship yesterday. I asked for an update at the end of the day, but I haven't seen anything yet.

[Aladin Alaily] 09:08:49
Cables. We're supposed.

[Aladin Alaily] 09:08:54
To ship. Okay.

[Aladin Alaily] 09:09:01
Reads, communicate, and all that good stuff related to the queue card.

[Aladin Alaily] 09:09:07
Raul, and I guess no, I'm sorry I guess it's with Rob.

[Aladin Alaily] 09:09:11
Still, if I understood from yesterday.

[Rob Finlayson] 09:09:13
Yeah. Helen's taken on the producing of the collateral.

[Rob Finlayson] 09:09:18
And so we'll just need to provide that information before cuz she's working on the instruction sheets first, since we'll need that sooner to a little more head runway to get things in.

[Rob Finlayson] 09:09:29
Well, I guess when would things go in classroom? Given that classes are ongoing until the last second?

    [Raul-Gabriel Edelhauser] 09:09:34
In that very last week that we got to 20 s. So.

[Rob Finlayson] 09:09:37
Okay. Alright!

[Aladin Alaily] 09:09:39
Cool, and for the team for us to actually build those kits.

[Aladin Alaily] 09:09:44
Mike, you had mentioned that you had, like at least a 100 of those absent bags.

[Michael O'Reilly] 09:09:49
Yeah, we've got a lot of bags. We'll figure something out.

[Aladin Alaily] 09:09:52
Okay. So then we'll be good.

[Michael O'Reilly] 09:09:55
Ideally, we're not producing a 100 of them, though. Right? Like.

    [Michael O'Reilly] 09:09:58
I don't wanna waste all of these cables on these kids if we don't have to.

[Michael O'Reilly] 09:10:01
That'd be nice to have some stocked.

[Aladin Alaily] 09:10:04
Yeah. So let's see what the schedule looks like, because people will be picking up their kit at the beginning of the day.

[Aladin Alaily] 09:10:11
Or you know there'll be available at the beginning of the day. But I doubt that we have 100 people every single day.

[Rob Finlayson] 09:10:18
No, we don't. We're.

[Michael O'Reilly] 09:10:18
Well, it's just these kids also are not gonna come back right?

[Michael O'Reilly] 09:10:22
We're gonna lose these cables, and they're 40 bucks each.

[Michael O'Reilly] 09:10:24
So, like, maybe.

[Aladin Alaily] 09:10:25
Yeah.

[Michael O'Reilly] 09:10:27
Maybe we just keep them in a central place or central area of that particular building, or something right?

    [Aladin Alaily] 09:10:30
Yeah.

[Aladin Alaily] 09:10:33
Yeah, so yesterday, we talked about a command center where everybody's gonna be sitting signing in and picking up T-shirts.

[Aladin Alaily] 09:10:43
And what have you? So this is where we'll well looked at, having those kits where people can pick them up, or, alternatively, we also we'll have that.

[Aladin Alaily] 09:10:54
But in addition, what we talked about also is having a lead in each sort of support areas depending on where that lead is sitting.

[Rob Finlayson] 09:11:01
Yeah.

[Aladin Alaily] 09:11:03
We we could have the kids there to.

[Aladin Alaily] 09:11:06
Let's just get the kids together. And then from there we can.

[Aladin Alaily] 09:11:10
We can decide what, how best to approach that.

[Rob Finlayson] 09:11:13
Do we need any of this collateral laminated? Hey, there, for the classrooms, or for the queue for the queue cards?

    [Aladin Alaily] 09:11:20
Ideally. Yes, but if it's if we cannot, then no, it's fine.

[Rob Finlayson] 09:11:21
Okay.

[Rob Finlayson] 09:11:25
I've asked Helen to take a look at at that with printing, so we that's underway.

[Aladin Alaily] 09:11:30
Okay. Yeah.

[Rob Finlayson] 09:11:32
At least the inquiries underway.

[Aladin Alaily] 09:11:34
Yeah, ideally, yes, because those will. Yeah, they won't last just like the cables.

[Rob Finlayson] 09:11:38
Yeah, they won't last. Yeah. And we can't spring background.

[Aladin Alaily] 09:11:43
Correct correct guessing is that if people take the kids out.

[Aladin Alaily] 09:11:51
And the expenses for them to return. The kits.

[Aladin Alaily] 09:11:54
They are technicians that work for us. So I'm hoping that we won't have to. Too many issues.

[Aladin Alaily] 09:12:00
But I expect that we'll lose some regardless. Okay, did we settle on the command center being in dB, 0, 0 4, pretty much.

[Rob Finlayson] 09:12:11
That's what we were talking about. I was gonna go take a look at it, find rather well, and go take a look at the room tomorrow and make sure we're good, and then I'll speak to Yasmin and the Ro to book it.

[Aladin Alaily] 09:12:21
Okay, sounds good. So from my side, those were the follow ups.

[Aladin Alaily] 09:12:28
Now I'm getting a little concerned with this whole Wi-fi and Internet connectivity.

[Rob Finlayson] 09:12:32
I wouldn't be any more concerned than anything else. I mean all of the pieces the bits and pieces are here.

[Aladin Alaily] 09:12:33
But.

[Rob Finlayson] 09:12:38
We just need somebody to do the work in the works technically being done without the Po.

[Rob Finlayson] 09:12:42
The vendor is just moving ahead. So I don't think we have any great jeopardy, but it would be great to have the eyes dotted and the teeth crossed, so we have no jeopardy.

[Aladin Alaily] 09:12:52
Yeah, agreed. And print.

[Rob Finlayson] 09:12:53
They're all working towards a test.

[Rob Finlayson] 09:12:57
So the the the end should be done. But on Friday, and then I think Tally is gonna start testing next week in situ the tent build starts on the 20 third.

[Rob Finlayson] 09:13:11
Will be finished 20, fourth, or 20, fifth, and then we'll do another test to make sure we have proper coverage of the tent itself.

[Rob Finlayson] 09:13:18
There was some talk. If we don't, of turning the boards to point the the wifi in those there, as well.

[Aladin Alaily] 09:13:27
Okay.

[Rob Finlayson] 09:13:28
At the moment, the original. The current plan, is just to use the 3 access points that are on the the 12 foot tower surrounding like right next to where the tent will be on the east side, the surrounding the pond, the reflecting pond just pointing them at the tent, and

[Aladin Alaily] 09:13:39
Okay.

[Rob Finlayson] 09:13:44
hoping that's enough. So there is backup plan in case it's not enough.

[Aladin Alaily] 09:13:46
Alright!

[Aladin Alaily] 09:13:50
Okay. And then you guys are also gonna talk about where is it now?

[Aladin Alaily] 09:14:04
Communication, tree.

[Rob Finlayson] 09:14:07
Yes.

[Aladin Alaily] 09:14:07
The communication tree. Yeah. So you guys are, gonna start that on as well. Still, right?

[Rob Finlayson] 09:14:10
Yeah. Yup, after the schedules done, I am gonna post A, or did I?

[Aladin Alaily] 09:14:12
No one!

[Rob Finlayson] 09:14:18
No, I didn't. I wrote it didn't post it.

[Rob Finlayson] 09:14:19
I. I have a note to go into the team to give people an idea of things that are coming, so they don't bother us, simply do it.

[Aladin Alaily] 09:14:23
Okay, okay. I think I saw that Rob. Did you know post something about expect?

    [Rob Finlayson] 09:14:29
I posted one about scheduling. I don't think I don't.

[Aladin Alaily] 09:14:32
Yeah. Oh, I see. No, no, that's the only one I saw.

[Rob Finlayson] 09:14:35
No, there's another one about what to expect with your shift and there'll be a sign in somewhere, and this and that the other thing, and there's a communication tree.

[Aladin Alaily] 09:14:37
Oh, okay.

[Rob Finlayson] 09:14:41
We'll let you know what that is, and so on.

[Aladin Alaily] 09:14:44
Okay, and in terms of printers and computers. Have you had a response from Rick on those things?

[Aladin Alaily] 09:14:50
Or still no!

[Rob Finlayson] 09:14:50
No I have to reach out to him again today. I'm gonna just put 15 min on his schedule, and then let him know we're meeting and make sure he accepts.

[Aladin Alaily] 09:14:57
Okay, yeah, yeah, for sure. And if?

[Rob Finlayson] 09:15:01
I wanna make sure he's good and that we're not just assuming I also need to confirm with him that we have enough extension cords and power bars to meet the needs, since that's on us to provide so if we need to order something that needs to be

[Aladin Alaily] 09:15:04
Yeah.

[Aladin Alaily] 09:15:11
Yeah.

[Rob Finlayson] 09:15:13
done Asap, too. So.

[Aladin Alaily] 09:15:15
Yeah, for sure. And I am meeting with Rico on a different issue tomorrow morning.

[Aladin Alaily] 09:15:21
So, if if you have something for me to relay back to, he's the director of Canada for Rico.

[Aladin Alaily] 09:15:30
So hold on. Let me get his real title, because maybe I can get him to do something here if we need senior solution, executive public sector.

[Aladin Alaily] 09:15:42
Rico at 9 Am.

[Rob Finlayson] 09:15:43
Well, one thing that I need from the rick is confirmation, that he has all of the printers and multifunction devices that we need.

[Aladin Alaily] 09:15:52
Okay, so so I will ping him. I will ping risk now and let him know that I'm meeting with Rico, and that you will be because he's away today.

[Aladin Alaily] 09:16:02
Hold on! Yes!

[Rob Finlayson] 09:16:02
Sorry, Rick's away today. Oh, okay. See back tomorrow.

[Aladin Alaily] 09:16:07
Yes, I know you're off, but I'm just real quick. But I'm meeting with Rico.

[Rob Finlayson] 09:16:08
Okay.

[Aladin Alaily] 09:16:20
Is there anything you want me to ask him?

[Aladin Alaily] 09:16:29
Rep related to Congress. Okay, so we'll see what he says.

[Aladin Alaily] 09:16:35
But but yeah, from my side, guys, this is what this is what I have.

[Aladin Alaily] 09:16:47
Anything else that we need to discuss here.

[Aladin Alaily] 09:16:53
Let's go around there.

[Rob Finlayson] 09:16:54
I don't think so. I think Mike and I have a lot of things to discuss in the next couple of days.

[Rob Finlayson] 09:16:58
About zoom and accessibility and whatnot with all of the free time. But we'll we'll do that when we're in meetings together.

[Aladin Alaily] 09:17:02
Yeah.

[Aladin Alaily] 09:17:06
Sounds good sounds, good I'm meeting with Mike right after this.

[Aladin Alaily] 09:17:10
So if we finish early, which is step 4, then certainly there will be some time there.

[Aladin Alaily] 09:17:20
Hopefully, okay.

[Michael O'Reilly] 09:17:22
We have the rest of the network migration, for classrooms happening this week.

[Michael O'Reilly] 09:17:27
There's some happening today. And then Friday, we did it over the weekend as well, and then resolved issues in very hall.

[Michael O'Reilly] 09:17:33
So lot of that stuff is coming up is coming on to exile now, which is great.

[Aladin Alaily] 09:17:39
Perfect.

[Michael O'Reilly] 09:17:40
We're working on goose neck microphones right now, based on an idea that I'll thegrudgedly admit that that role suggested only because it's a good one, me, I didn't think of it first, which is to I know.

[Raul-Gabriel Edelhauser] 09:17:54
Oh, you!

[Michael O'Reilly] 09:17:55
Some of the goose. Next we have on the old tower our microphones.

[Michael O'Reilly] 09:18:00
We're gonna see if we can re purpose them. There are 4 PIN.

[Michael O'Reilly] 09:18:04
Gosh! The name for it is escaping me right now.

[Michael O'Reilly] 09:18:07
But typically used for lighting. But I'm pretty sure the only reason the third, the fourth PIN is there is for a little light on the thing, so if we can convert it to an Xlr and buy a number of sure X to use which are Xlr to USB adapters with

[Michael O'Reilly] 09:18:20
phantom power on them, we'd be able to patch them into the Pcs.

[Michael O'Reilly] 09:18:24
Which is something we're obliged to do by the terms of this agreement with the Federation.

[Aladin Alaily] 09:18:29
Well, sorry. Say that again. Whatever we're obliged to do.

[Michael O'Reilly] 09:18:32
We have to provide a audio input to the Pcs in all of our rooms, which we have a solution for.

[Aladin Alaily] 09:18:37
Oh, jeez, okay.

[Michael O'Reilly] 09:18:41
I could buy a whack of webcams for approximately the same price, but I haven't nothing that I can do with the webcams at the end of it, so I can get these in about the same timeline they're about a 100 bucks a piece.

[Aladin Alaily] 09:18:51
Okay.

[Michael O'Reilly] 09:18:52
We would need a significant, some of them probably 50.

[Michael O'Reilly] 09:18:57
So we're like $5,000 worth of USB adapters.

[Aladin Alaily] 09:19:01
Yeah. Again, on the money front. We, I think we're still in fairly good shape, like, I still need to see the schedule to see ot and all that good stuff.

[Aladin Alaily] 09:19:12
But I think that, based on the cost recovery pieces, I know entirely concerned when it comes to that just based on the estimates that we had before. So I don't want us to make our lives miserable just to save a couple of 1,000 bucks.

[Michael O'Reilly] 09:19:22
Okay.

[Aladin Alaily] 09:19:28
Yeah, I, like it. Sorry?

    [Michael O'Reilly] 09:19:28
Okay, cause I can prime these and get points. I can get this on prime and get points.

[Michael O'Reilly] 09:19:36
Get my PC. Points. Man.

[Raul-Gabriel Edelhauser] 09:19:36
Yeah.

[Aladin Alaily] 09:19:38
No, no man. You gotta use my card.

[Michael O'Reilly] 09:19:41
I'm hedging my inflation here. This this is important.

[Aladin Alaily] 09:19:44
Yeah. Yeah. All good. All good. Okay. So I just wanted to point that out that you know, I think that until I see the schedule in terms of Ot, I think we're okay.

[Aladin Alaily] 09:19:56
When it comes for cost recovery. So even with the cables, the $10,000 ups.

[Aladin Alaily] 09:20:03
Actually, that's that's a another. I don't know what I let you guys know.

[Aladin Alaily] 09:20:08
Yesterday I sent a message to Steve and Brad to say, enough fooling around.

[Aladin Alaily] 09:20:14
I need to understand where this networking costs are gonna be coming from.

[Aladin Alaily] 09:20:18
And the response I got is, oh, yeah. Solomon was just inquiring.

[Aladin Alaily] 09:20:22
But we're gonna pay for all that through the infrastructure budget.

[Aladin Alaily] 09:20:26
So that $10,000 for cables or ups, or whatever the heck that was taxes in that's coming out of their 700 fund for infrastructure.

                                                                                                                        [Aladin Alaily] 09:20:36
So that is not something that is going to be tacked on to our well, I say, but to the Congress Budget.

[Rob Finlayson] 09:20:45
Yeah. Most of that was, for you know, Cling, and the labor related to that.

[Aladin Alaily] 09:20:49
Yeah.

[Rob Finlayson] 09:20:51
I mean, I will put ends on cables for a two-thirds that cost myself.

[Aladin Alaily] 09:20:58
The the last point I wanna make about scheduling is as good folks do.

[Rob Finlayson] 09:20:58
Yes.

[Aladin Alaily] 09:21:06
We have sessions at Austin happening at Austin.

[Raul-Gabriel Edelhauser] 09:21:08
Nope!

[Michael O'Reilly] 09:21:09
Badly.

[Raul-Gabriel Edelhauser] 09:21:11
For session for the Congress. Yes.

[Rob Finlayson] 09:21:12
Yes.

[Aladin Alaily] 09:21:13
Oh, how many!

[Rob Finlayson] 09:21:16
Oh!

[Aladin Alaily] 09:21:16
Like off the top of your head like not not precise like.

[Aladin Alaily] 09:21:20
Is it more than 10?

[Rob Finlayson] 09:21:23
There's only there I have it in front of me here.

[Aladin Alaily] 09:21:26
No like total.

[Rob Finlayson] 09:21:30
So total events, sessions, too, for.

[Rob Finlayson] 09:21:35
Oh, I don't know. I just on average 5 sessions per day.

[Rob Finlayson] 09:21:40
So 30, 35.

[Raul-Gabriel Edelhauser] 09:21:41
35. Yeah.

[Aladin Alaily] 09:21:41
At all. It's good. Okay? Okay, that's fine. That's fine.

[Aladin Alaily] 09:21:45
Okay. Alright, that's all I wanted to know.

[Rob Finlayson] 09:21:49
Yeah, we're gonna try and assign people that are familiar with the buildings to the buildings.

[Aladin Alaily] 09:21:55
Yeah.

[Rob Finlayson] 09:21:55
And for version, on and to like. Specifically, I'll be speaking to like Jeff and Shawn and Whatnot about that, asking them what what they need to supplement it.

[Aladin Alaily] 09:21:58
Yeah.

[Aladin Alaily] 09:22:03
But we need to make. I need to.

[Aladin Alaily] 09:22:08
Yeah, that's for sure. And for sure.

                                                                               [Aladin Alaily] 09:22:12
But I need to make something very clear. But you guys probably already know.

[Aladin Alaily] 09:22:16
But maybe this is from my own benefit of hearing myself say this. But Osgood has no idea how their rooms work, so we shouldn't right, so we shouldn't expect them to know anything about anything.

[Raul-Gabriel Edelhauser] 09:22:23
Never did.

[Rob Finlayson] 09:22:24
Well, if if we put them in their rooms, though maybe they'll come up with a little more knowledge at the end of Congress, and that will be a long term benefit of Congress, too.

[Aladin Alaily] 09:22:30
There'll be that comfort level.

[Aladin Alaily] 09:22:37
Yeah, that's right.

[Michael O'Reilly] 09:22:37
I would not ever count on that.

[Aladin Alaily] 09:22:40
Yeah. So the reason? So?

    [Rob Finlayson] 09:22:40
Not counting on it, but you know the probability is just above 0.

[Michael O'Reilly] 09:22:41
Okay.

[Michael O'Reilly] 09:22:45
Yeah, 50.

[Rob Finlayson] 09:22:45
Right? So it's in the right direction.

[Aladin Alaily] 09:22:47
So I'm I'm gonna fill you guys in on why, I'm asking this question.

[Patrick Thibaudeau] 09:22:48
Hmm!

[Aladin Alaily] 09:22:52
I thought first time. Maybe I won't, because it doesn't really add any value, but I think it does add value in terms of adding some validity to what you're saying is that yesterday Brent at Osgood made it very clear that as good tech team was doing U it a favor

[Patrick Thibaudeau] 09:23:12
Oh, God!

[Aladin Alaily] 09:23:13
by giving their time to do support, and I almost told them, you know what we don't need.

[Aladin Alaily] 09:23:21
Your people. We don't need them.

[Michael O'Reilly] 09:23:23
We actually desperately do not. I'd really much rather not deal with.

[Aladin Alaily] 09:23:26
So don't schedule them.

[Michael O'Reilly] 09:23:27
I'd really rather not have Shorora and Mark.

[Rob Finlayson] 09:23:29
If you don't want me to schedule them, I won't, because there's only 2 that signed up Mark signed up for a good number of ships, and Shorter signed up at a time that does not work for us he just said here's when I'm available

[Raul-Gabriel Edelhauser] 09:23:34
Oh, yeah.

[Raul-Gabriel Edelhauser] 09:23:36
We are here.

[Rob Finlayson] 09:23:44
that had nothing to do with when we needed people. And so, whatever.

[Rob Finlayson] 09:23:48
And he's the one that was completing on the team.

[Aladin Alaily] 09:23:50
Correct.

[Rob Finlayson] 09:23:50
By the way, that he needs all the written instructions because he can't go to training.

[Aladin Alaily] 09:23:53
Correct.

[Rob Finlayson] 09:23:53
So we'll make sure that it's not required for him to be a training.

[Aladin Alaily] 09:23:57
Correct.

[Michael O'Reilly] 09:23:57
Well, they don't need to. I could say this here. I don't care. Those who are useless.

[Aladin Alaily] 09:24:02
Yeah, yeah, yeah.

[Michael O'Reilly] 09:24:02
They do absolute, Jack, all, and there's no way that any of the events that we're having in Osgood, like 1014 that they're going to be able to serve any useful purpose.

[Michael O'Reilly] 09:24:09
Besides sitting there and complaining. So I'd really just rather not see them or hear them at all, for the entire they go take a vacation for the week, volunteer for that.

                                                                                                                                                                                [Aladin Alaily] 09:24:14
Bingo!

[Michael O'Reilly] 09:24:17
So, yeah.

[Aladin Alaily] 09:24:17
Bingo. So so this is why I was asking why I was asking so if there's an opportunity.

[Rob Finlayson] 09:24:22
So I could schedule a mark, and for some afternoons when we don't have anything over there, we just have things ending.

[Michael O'Reilly] 09:24:28
Great he can he hold the door like, yeah.

[Aladin Alaily] 09:24:28
What? Yeah. Remember the but I don't want to pay.

[Aladin Alaily] 09:24:34
Sorry, but I don't want to pay him overtime.

[Rob Finlayson] 09:24:37
I'm sorry.

[Aladin Alaily] 09:24:38
I do. I do not wanna pay him overtime just to sit there.

[Aladin Alaily] 09:24:42
He is not an insurance policy that I wanna pay for, because remember what I said yesterday, right?

[Rob Finlayson] 09:24:44
Yeah.

[Rob Finlayson] 09:24:50
Too many clauses and exceptions. Hmm!

[Aladin Alaily] 09:24:51
That, like, we have the insurance policy. No, but all drugs aside.

[Aladin Alaily] 09:24:56
If we need them, we need them. But I'm just saying that they made a a Brent, made it abundantly clear that as good is is helping uit, not university, but uit by donating the time.

[Aladin Alaily] 09:25:11
And it was a very, very difficult thing to convince the staff to do so, because they don't feel as though you, it ever gives them anything so just.

[Patrick Thibaudeau] 09:25:19
Oh! Give me a break!

[Aladin Alaily] 09:25:21
I'm telling you. I am telling you. Yesterday yesterday I was like, you know what? That's actually Patrick.

[Raul-Gabriel Edelhauser] 09:25:22
Voice, of reason.

[Aladin Alaily] 09:25:27
That's the reason why I missed that cause. I had a meeting with Brent about about this stuff. But, anyhow.

[Patrick Thibaudeau] 09:25:34
Good miss much. By the way.

[Aladin Alaily] 09:25:36
Yeah, I heard, Marie went off a little bit on Congress communication, apparently, but whatever it's fine.

[Patrick Thibaudeau] 09:25:40
Yeah.

[Patrick Thibaudeau] 09:25:42
Yeah, whatever.

[Aladin Alaily] 09:25:44
Oh, I I can chat with her, if need be.

[Aladin Alaily] 09:25:47
Alright! So with that tidbit of fun, news, and sunshine, I will bid you a good day, and please do reach out to Patrick, and I, because we may need a break from this Microsoft.

[Aladin Alaily] 09:26:00
What 6 h meeting that we have. So any interruptions are welcome.

[Rob Finlayson] 09:26:06
Oh, do enjoy it!

[Aladin Alaily] 09:26:09
Oh, my gosh! I'm having too much fun already!

[Aladin Alaily] 09:26:14
Okay. So, Rob, are you good?

    [Rob Finlayson] 09:26:16
Yup, I'm good.

[Aladin Alaily] 09:26:17
Okay. Patrick, on your side.";

//$text = 'You miss 100% of the shots you don\'t take';
$numberOfTokens = ceil((strlen($text) / 4) * 0.15);
$completion = 200; // This is just a guess based on logs.
$cost = $GPT->_get_cost(1, $numberOfTokens, $completion);

print_object($numberOfTokens);
print_object($cost);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();