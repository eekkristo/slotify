Currently, there are quite many issues that have to be resolved before developing more features.
The following issues I have commented inside the codes. They can be found by installing ``TODO`` extension in Visual Code for example. 

```composer
#1	TODO: Implement Dispatch.php file inside Routes file that fill handle all of this data so we do not have to define this ourselces
#2	TODO: Implement update email and update password change
#3	TODO:: Make this better. Right now this kind of is all hard coded into here and due to that really hard to add more options
#4	FIXME: There is a bug if you add a song from your current playlist where you are another song the UI will flip out and render double your playlist
#5	FIXME: When you create a new playlist it will not be visible in the add to playlist. This is due to result is not empty. 
#6	TODO: Implement a delete song from playlist. Currently due to clearing optionsMenu we can't display delete from playlist function.
#7	TODO: Implement a $_POST check that deals with the data charset escape. Since js calls can be changed we should always assume the worst
#8	TODO: Implement a global json_encode parameter so that we do not have to render it ourselves constantly. Create a function for it and call it that way?
#9	FIXME:: Due to redirect back to root to store flash we are also taking the navbar and playbar with us 
#10	TODO: Fix this later with new function for redirect
#11	FIXME: Seperate Album and Artist Controllers to seperate files
#12	TODO: Make this better
#13	TODO: Make this better and
#14	TODO: Implement 404 artist not found designed page
#15	FIXME: Fix the search result. If no data found do not display any data about it
#16	TODO: Add song artist name 
#17	FIXME: Fix artist name in database 
#18	TODO:: Implement a global $_POST check. By default we should always escape charset as we do not trust the end user. 
#19	TODO: Implement a check where we will authorize artists / label owners and administrators with different privileges
```