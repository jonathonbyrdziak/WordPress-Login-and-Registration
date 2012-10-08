<?php 
/**
 * @Author	Anonymous
 * @link http://www.redrokk.com
 * @Package Wordpress
 * @SubPackage RedRokk Library
 * 
 * @version 0.2
 */

//security
defined('ABSPATH') or die('You\'re not supposed to be here.');

/**
 * Admin Pages
 * 
 * This is probably one of my finest ideas for a wordpress script.
 * This script uses a hidden taxonomy to manage adminstrative pages.
 * By using a taxonomy we can easily handle setting revisions and redeploy
 * settings from previous states. We also have a number of other tools
 * available to us that you might not have thought about for an admin page.
 * 
 * Have Fun!
 * @author Jonathon Byrd 
 * @see https://gist.github.com/2068458
 */
if (!class_exists('redrokk_admin_class')):
class redrokk_admin_class 
{
	/**
	 * The id of this page, used as a slug and used to load this class from any
	 * location within WordPress
	 * 
	 * @var string
	 */
	var $id;
	
	/**
	 * Contains the post id of this administrative page.
	 * 
	 * @var integer
	 */
	var $_id;
	
	/**
	 * Variable determines whether or not this class will become a post editor, allowing
	 * you to modify or create a single post type.
	 * 
	 * @var boolean
	 */
	var $single = false;
	
	/**
	 * (required) The text to be displayed in the title tags of the page when the 
	 * menu is selected
	 * 
	 * @var string
	 */
	var $page_title;
	
	/**
	 * (required) The on-screen name text for the menu
	 * 
	 * @var string
	 */
	var $menu_title;
	
	/**
	 * (required) The capability required for this menu to be displayed to the user. 
	 * User levels are deprecated and should not be used here!
	 * 
	 * @var string
	 */
	var $capability = 'administrator';
	
	/**
	 * The function that displays the page content for the menu page. Technically, 
	 * the function parameter is optional, but if it is not supplied, then WordPress 
	 * will basically assume that including the PHP file will generate the 
	 * administration screen, without calling a function. Most plugin authors choose 
	 * to put the page-generating code in a function within their main plugin file.
	 * 
	 * :In the event that the function parameter is specified, it is possible to use 
	 * any string for the file parameter. This allows usage of pages such as 
	 * ?page=my_super_plugin_page instead of ?page=my-super-plugin/admin-options.php.
	 * 
	 * The function must be referenced in one of two ways:
	 * 
	 * 		if the function is a member of a class within the plugin it should be 
	 * 		referenced as array( $this, 'function_name' )
	 * 
	 * 		in all other cases, using the function name itself is sufficient
	 * 
	 * @var callback
	 */
	var $callback;
	
	/**
	 * (optional) The url to the icon to be used for this menu. This parameter 
	 * is optional. Icons should be fairly small, around 16 x 16 pixels for best 
	 * results. You can use the plugin_dir_url( __FILE__ ) function to get the 
	 * URL of your plugin directory and then add the image filename to it.
	 * 
	 * @var string
	 */
	var $icon_url = "data:image/gif;base64,R0lGODlhEgAUAPcAAH0uKH0vKncxLHUyLWQ8OXU2MXY2MXc2MXk3Mnk5NH8/OmVCP11SUWZDQGlEQWlGQ21KR2VMSnFHRHlHQ3JtbH57e4IvKYsvKIc0LYc1LoY1L4c1L4g1Log1L4k2L5MwKZUxKZkyKpozK5szK5wyKp0zK5s3L5w0LJ00LJ00LZw1LZ01LZ40LJ80LJ41LZ81LZ41Lp81Lpw2Lp82Loc6NI47NJw3MJs4MZo5Mps9Npw+N5U+OJo+OJo/OJs/OKAzK6E1LaI1LaM1LaA1LqA2LqE3L6I2LqM2LqI3L6M3L6Q3L6M4L6Q4L6M4MKM4MaM5MaQ4MKQ5MZtAOZtCPIpJRZlHQJ9KQ59QSpxSTJdXUqJQSqJSTKFVUKJaVKNfWqJjX6hhXKhiXZpqZp1rZ59wbKhsZ6JwbbF6drJ7drR+eqmCf4GAgI2QkJOTk5WSkZeXl5ucnJ6fn6uHhLaGgrGIhbWJhriIhbqMiKKYl6OYl6KYmKOZmKSYmKSZmLedm56foKCgoKaioqamprSqqbK1tba3t76+vsCmpMGxr8W4t8/Bv8DBwcXFxcjJydPS0tPT09PU1N7Y2N7Z2N3a2dzb297b29vc3Nzd3d3d3d3e3t3f397e3t/f3+Dg4OHi4uLi4ufn5wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAUAAKEAIf4oRWRpdGVkIHdpdGggTHVuYVBpYzogaHR0cDovL2x1bmFwaWMuY29tLwAsAAAAABIAFAAACP4AQwkcSLBgwTA8pPBIqDChljAFGVUJsqRilItLogRRoYPLwEE1glyEQrIkySg20Fiq1KcDE5JLRoAYIWJJSSVWEEnis6GkCgQQHhAQoKKkCTksOwQhqUIMIUFrHoAoWYKMpJZMokBR0eXOnDE0lpK8IceSHpckmagoUaJoySBaEplFa7JuEBx0IFnCs+ElFCZ+SSopIYVOKEd7ezK1AEBE2hBlBOrlq+SiigkMAGSEEkSHncN7O1SOosIMmwYfRgbZEkpvy9EqziyiIGBpZRNpHF3FQHEjFk1/IlwIomTwFD+SAkk4kIBK5FCNKiw4cIBGli+DQjH60+ZNoUwCPxcZgtPGDaBGoT6F1/QJVMFPmeAbnE8/IAA7";
	
	/**
	 * String to image
	 * 
	 * @var string
	 */
	var $screen_icon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAAApCAYAAABnYvZwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6ODlEQTM1RkE1MDMyMTFFMTk0QTJDOTc3MTEyQTk5MTUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6ODlEQTM1RkI1MDMyMTFFMTk0QTJDOTc3MTEyQTk5MTUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4OURBMzVGODUwMzIxMUUxOTRBMkM5NzcxMTJBOTkxNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4OURBMzVGOTUwMzIxMUUxOTRBMkM5NzcxMTJBOTkxNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PoZY1xkAACI6SURBVHja7F0HfFRV1n9TU2YymUkmjYQWQuiGQGhKEURCFnQNTQQFCxY+dXVZFFD3EwsI6sLqygIqKqgUgYCUDUgRkB4gSEsBkkB6MplJm0yf+e6Z3JPcPGYmmZi4n/58v9/NTN68e+69757/abcJHA4H5+paFBXC/XF5f71X8J3zMyhocqvya7XbBeSDTezloMn5nZThaCV9jkfbQevsaCWNhnze1Imhg7T4eQWtqR+lzebn1/MOOuI/WPe/ezGMLyRJxHzid7YT7STZSLLCJ8kL/9s9MQgPWGwZLG0nHaTHpCbMR2kJeYlPw4aAdVcvF3QELpiVf89BadtbQV/I1pGhYedcSAXntTBS/TD5mNsGfZxH0kWSdtLvzV1tVa6rC+qxmqQs+n8PWlb/X0gT2nWEfm+xRnDB/CCQJDRJme9iphMRAGaSjCSZ6HcnKEh5dhflCJkyWPpSBhAsbQsvsYzHMQCVMomlYaF1slB6dj7D0rZjm6VMO/lAYEHiYOpn5teNRx/zse0V8erIb19TIBAQ9PeVStIjVKpfzHkWq5UzmC1cjcHAma3Wo8CIfTt13OLu+Su38490DQsd1R4oMJrNXLGu8iNS/su0rH9GqJQv+Uqlv4im2Wrj9CYj+W65RQG/mgAhyx0QeJIKmd+HJF+a/CBduHBdnZJyoldaWna8QFDfR8SCdajVisLx4xNOP/bY2OvkVi1NBgQECwbKEAgAoO2P6cyZzJCUlOO9RSKhk4n79u1aMGPG6NsUYAaajDQhUyMQkFbAhAlvTI+OjigbOLB78eOPj8un+epoMjJAdWotBgQS2lYZ0Jo2bUlSXZ0xcM+ed3ahtP/ppyvKFSu23depU1jx9On3Zg8b1ktL6euZ+lnc0Jey9JOSXp8KlU9NXbKN1k3Pvjcoj5VM4ufDg0aHy/xSu4aGtikj6k0mwog6YBgAw//g/UdPpQuQIQgI9eS3dlEHUH5uadnRZYWasVDWq5HqH7qFhQ6X+fi0CX0AfTUBfEVNLYB+PQBibZ3tjAsNgJLQh2Eo6Cz5xx/vjNu58+TE69cLh+j1RjX4bnz/TQCoICkmpsPh8eMHbX777Vk/kdtVFBAm7FRaFkpcoB9AkvKpp1Y8dPLkteTSUl1vljbQlMl8NZGR6isJCd2PfvLJC/vI7WoGbEYqOZ30Nm78MWblypS/3bhR+GfMr1LJbw0e3HP3pk2LNpFblTS/nmFYO62XlLY7cNeuUzFLlmx6ITu74AGgo1IF5I0Y0Xc/AVbu4sVfL8M6Av3OnUPT+vbtcuzrrxdspW1G+iZK30H5GOkrFy36YsSBA+cfuXmz+H6gExgoyyE0thLAfU7rqKdgsGEHQacETA5WjOuvDPi6rYGAV2GFltPp9Q1gIECASsvhHRAg3GhnIBwjQHgQXtBfOwRv6xkeltBWQGCvSr0etA9ns9uXETAsokBgTQp/2mYFkfyRhKEmnD6dOVGjqeroivldXcAYQiLMx4zp/89t2/6+jtyqIKmGMoWdluWD73b27A8ePns2a0pJiba3pzIQaHK5n6Z//267d+9+ew1UnzKdlUpy5YgR8/738uXcGXwwYd64uOhdhNk+ofWqpmCw0XpB+8HkCB058m9vXrqUk8gyPLRr0KAe6WfOZMS7oh8REZxBtEPKunXztjLtRqCKKf2g4mJt1KRJb72ZkXF7FEs/KEiRN3PmmCXvvDP7MG0baAgLdhBIjA4hEtHQbjK/0SqZrF0YUuHvR6SnbdBtjSYsNDBwb0pBCVQaUBc7XOE/k9xrl3ItNhsA8PaJmrpU8m/0QJlfcliAXC0Vt32sAMytILmcM1usw/sJbGEHSrelMlrAyZhr1uwd9Morn/3lgw+2LibScBDRAIHQWdBRIpHojgT3XTpheaVDz5+/YZk6deQ19BUYqQtlBROz4+l9+9IW1NTUhbAM5Y4m1MNksvjn55fHr19/4J60tCxxcvI9OZRPwNxQLVq0bpnd7pC4y1tQoInfvv14TGZmvi4xMaGQ1ktI3wF0ctioUfPfunIlL9FVPQgTR7irW22tIYTQHUm0Z1RAgH8mkfDVDH1fCrKwBx74+xvXrt0ew6dvNJqVVVX6gKefTjrCaBMbAgEq11ktEQ/uKfcb1F5AQDAQCT2IaIeS4zV1mQBAkvoQIExoTyBo9fp8AoQD4CgPkPkmRigCAtsDCE4vj7z8QJm/E/SpK7aEjXl5ykHKmEGDB7+4fOvWY68VF1fE2u12J0OKST18iHaC5Ofn15B8fX2d96QEXAAIZAZWUubmlgw1m61XR426q4hKRSFl2CAAwcGDF16C5yQSiZMW0oQE5QJdoVDolumIqXav1Wq7NnJkP5CefsS36Pn996emyQnYIT/mZesE37XamujKylrZc89NPMRoA3gHoYmJry28ePHmRBnhM6gHvAN8F/A/1BXa76luFRXVsZmZt5XPPDPhCG23hAr0sPHjX3v14sWcCUjfGS4i9LGOpG5di4oqbiYlDbqOJiV65UDE3+pwKH6NkGGE0umMz2WkTFC7F+poMAGVtvr2tvsVqQ7mVDL5c+8lzP0XMMFjjy2fRhjrQegU6GRgSmAopVJJVHaQM6lUKmLLBnIKhcKZ4Dv+Dt8hD8t8QGvfvnPTQfpTgQZ9qFqyZOO96ek3JgGzBgQEOOkGBwc3lAEJywT68AzQFjPCgaH/yLffHh4A/UTq3w2AifnhE/MiWDHvrVtliZ9/nhpLAQB1C7n//oULzp3LToa2QbnYXmB8oAvvIyQk5A76wNB8+jk5JQ9R2g30CcheTUvLfgjfGyT4joDDvIcPX5xENRQQFYiZMKqQ/C76NRjEVyrhZL4+cY+HKu/5qqyykglvtffldFhJO4W/SmGktKhQNVdQV/Pk1pf/XQwdBJ0JHY5SGb57ksxsx4OkhGehQw3EOUcpd+3arZHJyYuf27Fj8Wqq6uWpqeemV1cbOgIjAJNBXqDPNxUgv41oTCtx+M1ms5MuJPiO9Ik/MPzLL3+wENv6c2KKDQRJC4zvjEeSvPDdRPywurq6hrwofefMSaqk4JTMnLls1vnz1ycjyJE5kcEhH2pCuI9A5NO3WCz0fYjqaDAAGuVDQDb/woUbTpABeODdIn2gAwnoOP1GvTGcDSF7bRsYzRauuFLn8jepSAwMzilbYFopSINVdcYk8nVTS8sGJ9RoMXvNkMRx5Wrt9i+8yeOuLJmPLycVizhwtCXNmlbEFhcJuY5hYVzGzeLZGk11FEp1ZGqW+ZtzlOF5f3//BvMIwQDMSBhgInlkBwBhwoQ3nsnKKhgBEhWeR/C4KwN9EagTMA981tTUEHva2AAG4tiPPn786rHCQk00MDDSgfojmCEf0IG8wKzR0eEXqLT2Idpwcmpq2lyoD0h9KQ1dAx3Ig2CFxNaTTx/+B/oA3NjYqAvUHPKbNev9yQAyAADQZ+kgfQAB1AvaJJWKa9kxEq+BYHPYudwaveZkjSGTjn6QnnQIyadIKRLJOvpIgvopZOEx4eHNOpVigaCvVyAkjPltkeZ4Cx61igWcViYU5oVIxJlqiSiDaJ4r5H53b8tyNIxw1rexi480OEAk9O8gESujFAG+oYEKt4AQCAWckIDGV+5HHMCaKAAASCvWAW5JlIjPtNCpwAgoxYEGcQCD5s1bmzxr1tjLp05lPAhmEILAUzlAAxgLAApMBgwEEh+lMTAP5iW+zVCdrjZYIpG5DO0Cw0IbQXID3bi4bjfBZFmzZm+PvXvPzsX2o93O0kCtiJrgDr+L1A3yAV2gD+AfODD2BtCfM2fluD17zjwJ74UPMpY+5AfhAfcHDOieygyqOVrlLRIGNmQaTKeYMQgfGrZSnK01yIiv0U8lq4kMDghwDwSQHgKur7dlSwWCgkt1xvzmgEBDaxDtuAqRW64VfgEtq4AJffpmG8w4MOU72mCKudtg6AbhZjD3XNlGAAQR+S23so5DB7PZcK9e70zQadDxyAhqtdqp8lEzgHRDswY6l9jeQ0pLddHAEMDMzZUFZRiNtVXDhvW6fPJk9nCgj8475EdzAsAG94nP0aWkRBvWrVvLBlwnTBh8a/36AwO3bftpNACM7+O4Ct2i09xc+BjS1Kkj8les2D6COPBPAl30BVzRh3sINLU68PamTYu207CrtdVA8BEIIGR1ghm+96fOGkSAIg12h8Fm9yzp6KCm1865Siy8Mkrh35xJ4iAawSEXCVUqsai3hDT+s1JdWWvKIh8ZVHP6UjUMXKCG9v5YpbeY7A6wn3rFdojANjWCgJhFArD9JWIuT1vN+QQGNasBysvLuY4dgzISE/udJYx0cezYAWBjB7/00ur7d+1KSwQwYRQI1T2AAegSR7bL1au3eoQRUww63VNZwNxlZWXcW289lvLgg8OK4uP/ZzhoEVaCAnOBeQSAAwaqqzP6wn13dKEeCM56pnYoN28+MujSpdw48FUQBJ7qVUlcRpWbmQ0sfZUqoArCrDt3nhwBGgA1jTv6ADAQGPWapPtOOr5h+EVAkAoFYF/9zAy7g0YIoaN1AmKSEB4QNGu308q03MmWSLmxIap3vR/5tXGTgxVx2yu8Ko4Ll0pAtafzBsOUEPiCcDOkkzV1doVI6KfQVXaJDA5q4iijRgCxo6k1cJ2D3TMRmiIgpdevX7qqW7cOeXTUU0YYKQykMTASOziENj06tiaTxYcNGboqC51jAEFSUkLK888/COFNXza8yIKBdbILCyvCmwMC1AU+iX9wa926fYPOnbsehw57S0CAjrA7Mw7p9+gRlQf0MzMLerQEZEC3trYWBut2bNny+npmALL1phGRsKa/RgTHUB9BQEwhodZqU1gcjh7+QuGornK/aE9mUb0NTpDt4K54FXZVKVsVvSmrquY6mS0zyNft3uQjfoCGmlX8OSylFPROb/pYdV32wAD/LuEQFm3QCvWOMgAhr1rvZChPIIAOBts3ODigkICgkE5tgEu+cOG6B/LzdT1AUrI0MALF+hxgOrmzs5GZqqqquJAQRfbXXy/4kjKDXKHw1xFmUbHmFEZcgMEgAdCUSlmzGgFA1bFjSPlPP11NAEmNoVVP7Qc/JTY27OesrJK45ujjBSBjzS1P+QBkvXt3PLh//9IPyS0dM73C3iogiARCiAyNlHHcAfZ+mJdRI5i0RgB0hft/fPkIBdZvhsU3hI4ePZVuoAyKc1ycYxN1drt/kcms60QYqWHahgCcZaETDOV6Y0MUwx1zAgiAGcaM6Z9OpZRzQt7YsQtfJBrhnnAXwQfofHQykWF9PEwbAQYFUwe0zsSJA2GCmwYH4aqr61RBQaF32OIsbSzTHTMDw6FZdOzYFScIWIfdXT5oO8FzaXl5pRLDsq6eQ9MGrrS0bCcImqMPbQbgR0eHnpg5c8wG2mZ22oe9VVEjcArbYi4STFLT2Wyp3G/oIqBwTgcmgKilWgEHA0OKzFbC76YGIDhBAJKUMJGmzsB5MilAyoFjDJ08evRd4OAriO0bu3r1ngcvXrw5ElS/O1MHmRV+QyZyZxKB1gHzIDxclbVq1Yu7qXkgonayW/qoEdA38WSDwyc8C5qpuagVgqCiooKbM2d82u7dZwYJBD4un8WgAEu/OXML6YOASUpK3DxnTtJ5qsnRN2iYrSv8bzAUjEXUGE1XviqrPMH9Bi8CCDvVChCZKoFPQX0orqnEFhHziJhGV4orOIy98xN2MCRgurlzH6h46qkVM5588h8fpqVljQRmwoEhV/kxTg5Mjj6Eu3JAG4BzPXp03B5GKlqpZrsjDx8ISqW8CkHnij4AGp4DP4UPAlcJ6gMgmDFj9Kbly+fsh4gUAs3de8KRcpyC4Y4+AAZBNnZs/MeLFk0/wjGT7NA3aPCH/huMVKCt4Mx2x2fcb/uy0ZcKEqZKKGiYs98whlCvEYRNmNiTg9mvX9es6OjZ87Ta6kDIA8wEHe5OqmJ4ExgKfgPAuJO8wKTwnEQiMvzrX8//h2EIn1WrdsW6k/R4Dz4rK/WBAQEqt+1wWgwEjJ7qzGpByqRbSH1SDh1K7+TJj8Cwakvpg4YF+uPGDfxk06ZF6ygI9KyD3ERo/drc45yKbTR988/iijW/ZRRQM8lC1WydWixWNowl4AxPMI+IRrhcUNbApO4kKXy/dCmnh0ZTGYhSFRI7QspPkA+cwHHjBhx3R58dGIMUExN5jmtcY+CcDHfzZnEUmm6uEjri7qQ1PoMDap7qjPWGMPGAATEQwQGBWHnuXLbCUx2wfHY+lLtneSD4nGs6Td3GmkS/OhAgXAogyK2qTvmoWPsyxzWVoL/RC9e9WkMloogG/4CGTp1RI5Hn+UOs7YsSFcYKMAzqzjlFiTpmTNyP8fExtzzNUwL6AAIoY/DgHsepNkCTSHL9emGMK2eeZUL49FQGSHOcO+VpQAzqAiCIj++Wsn//0g8ok9YRMKo9Ob1IH+rpiT6aW4wm0DAgsLoCwa9mGuFilQKjedv68spXaUd4vQNDblkZRxxS70HocHyWYTDBXKO2nuftZPlJwYo/hcjlUnZADU2jDE2VW5MFHUzn2AydiAdA4E+75l9g+4ImALOCSNTd06cvneHJLML4OzDQihXPHuYaF/A4I1M5OcX9XOXHeUxo+rgrg3WsPZkrQAM1AQHBMmpWOoV9cbE21JNpxK6hcPcMgB1AMH58wqpvv10IICjnGhfuuAVBq4AAkt3IxHKlMFmrmaF8HQHCDYPp2y2aqmU0hmsG0+LRU+lec95Obc2RTIOpvJnHAGgFdNDvIh0LkLYlEHCZ6Ux14Iwufj6TYM4Riw+BoD50KhC67zy0e9HxQwC4muOPjKTT6bjIyKDzkyePT3n//TkngZFNJou5JT6IWh2Yz3OQnWuHCRP27NChg0sgoNkDYGIn23nNN6R8jUbDJSR0/z41dcn7lEnBrIRQl0OjqQptbsDN0wUg0Gq1MK1jzYYNr37BaIJmQdAqIAAIThcU6ehkNK6Tn68Sphd4uqKCgrhqg3EM+foSdVjsrWVAX6EARlzzmnkMYv9lzMu2tYOiE80NVz0dJJEsgHByE2EAYwiieo2QV1ntNuSIdjWO3roDQP0imVryvE07bFjPvTt2vLmaaxyVV1itNos7sCEQgKkDA/3Lucb1vc61IPPmrR3rjsFRI8BvCoV/lUAgDmwNo0L5wKQjR/bdt2PH4o+p41rDjJfAOEZYc9NCPIEABMSUKSPWr1370kaqaVoMglabRqS7LOvKKmHhuP/kYEX/UL0+wtMgGjCJWi6PeKWDYMEHRZoF1NFs1RUiFqGU93RZKeB0lGHaDAhXbud3IZWPn98heIq/RDKjk1p9x4Q7pwqnGqHOYvU40utpGjYwITh+MAA2enTcwXnzJn9z9929r1FpZ6BMJLHZ7DZ3GgFDs3BFRqpzucZVbODQyNLSskZ4AgKCISgokFhjZq+BgNNGCAjOERBspNoad5BA81JcU2MI8fOTew0EqBvSJyDYxzXd4cLWUhO8tVMsgMlgC5PwC3pjXp+q6ojmRpPDVUoYRHtlYaT6H3QwqlXXQLnfSpK8zXa02mbf8O8S7WUv8/1IGL/JDVjeCTNnYcmp2zY7I0b1k+7Kag1NVlZ55YAQOuD8EZv3OLF5N5Nb+Vzj7hImysxmHx9JtTv/AJmZAoXd6UHy8885Ha5duz081M0AKeYFIME8I6UyyGsmBW0A+Y8cuZTw7LMf3UeYNYdruqmXc7lwba0hWC4P9Jo+TgoE+vPnfzr8ww+fyeXu3DSsfYAgFQpA7QBTWXKNZvHV2rouzWkFcCRh/lFZVRVEjBa1ptzWjmjrTaZRN0vKoK1/be+yWMcOTKOymjrOW9uXDatCJCU1NW14cvJbemIS/YNrumubc8OqPn06Xz91KtOlRoGEESmRSIj7EkE+/yVLNk11N0GPndIAZlFlZW2gJ0fVFYhwJBsjVlu3Hps9YkTf448+ep+WCZiI09NvKD1pTXf0cdUarjr74Yfz444evXRs1Ki7KpnImL0lWqFV4VOpQACFZJMEm0Jp0/XGHJjY1twVHCAHibqQSNkh3O/5EjRGjexM+NFVAkbBcQSU4Dj3CH4DtQ8MdeTIz4lJSa8/Tx1+OdUGIPHskybdk8HS4I9TIINJpRLQBDDHOeTMmczYQ4fSp4Oj7ml8Az4TEwemeRrZxrEBRvM0AAnBhP9/+eV+WF8NqgWiC6DapTt2nOzpaQzBHX029AwpL6+k++LFXz9N24jvSNQSPm8VEEQCgYVGYsA8KssxmguJViiGMKnHfIQxaHRl7u8aB+AjECDozUbuWpGG89TJwOTg6LHTj9Euh3u46AaeO30648+wOJ2rn/KuoB3tGDCgu9bf30eDz7KgYv8vKqroxtVPIY96++1vnsAolat6AS2s09ChvQqbY1SoH4R0WV+FncaNGur8+ev3kTb8jQWDRCKSeBIW9dvEmLjq6uom9Pnb3QD9ixdvjn3sseWzKH0Z17gtpaDNgSCoVzew2xjMsynyRiuA+UScy9lEKzz8O0WBM9UYjFyhzXzRnemDTArMNmfO+D3QyejUsoNTrH8BzHb2bGYybIfC1e8HJacSzxIT0+E0/O7JlLh69VbPBQs+/1Ny8uJnzpzJuhumcLganGKl+bhxA49YLFajJzMOn7/77p7nQIuhMMDlkewgGLSXOOjJc+d+PAUld35+eQd3fhQLtKFDY89B8ADp40gzOxgI9GFZ6PLlW0Zy9WtH/DEy1eZAoJeBRi8KvNEKcNG9VX+XWgG2fyzQVHDZNVXnzGGqI65MCpBcaN+GhiqLly+fs2fatBHf4sIU7GTc4oQNq0K+M2cykseNW/Qq7WiQeJbZs+9PwfW4riJImHft2r0Tjx69nMDO3HSlDeDZwECZbsGCh783Gs16VxIbzThMTz2VdP6uuzr/jHOfsB3sVizI1MePX31o06Yj/UBql5VVRriqC5/+yy9POh0aKr+F67Qx/MzShzzwDnfvPjOFaMBQHhAE7QEES2u1AkxFkPn6jPpLRNCLvycAwOj5zZJS7rJe/31Jr87LsmtNFa5MCox2AOOGhakgylG0atWLG2AwCObO88HAn2pBNcOkIUP+8gbtZOvjj4+71rNn1D4WDKzjzgICd4Vwx9xokiUkxKbGx3fLOXDgQmdX84wQ0Li2euLEIRemTh2519dXVIRmFe5CwQK63p4vjv300724t5DLurDvSSoVG4kTfOn++wfuB+3J0udP7YB8ly7dHDFp0tvzqR/SfkCgU5FbrRVgkM1PKHzit8r4MMIOe6oC8G+UlHAXCwprj5ZqDq8t1b54Vir+uE+fLrfEYlG1OzMFHVFi0pyng3/FGza8+hlMGRaLHfnAYMhECAa+mZSZefuBe++d/xSNAtXMnHnf1wEBPhm4igvBhNus4DpkPi1XYxd9+3bZv23b3z8ltyo6dQq9imYbX8vgrFa1WgE8UPLkk4nHhgzpsQtNPb4JwzJrevqNsS++uGp4eLiqFKdyuHpPQD84WAH+aMl77z2ZQgC3FtYYoFbAdrH0oWzyfibOmvX+vVzT7fWbD5/C7hDddNW5eqOpq1tb08GdIH7gahpf52uFUNAKXGGJoLuvNLwZXrplsNs34D/ZBvNN7nZ+t/ZgWqgzu6/RlTpjpr2gSBomEbd6/3uLw2HVWW26Eou1iNT90g2jGQb68qRSya3+USEVgwb1sPfvH5O+efORO7ZDxJAmdFqfPp0v0XcIIWnBli2vf7p06abs//wn7eGcnNIRYMez0hinS2BnZ2UVwP7zK4G3nntuQvqpU9e+27PnzJu4URbuqIcj17jVo6tQKNQJbHxSpx+OHv3wLTpCy8G07e++Ozqf/O7Pahd06usH3BQ3qFCsI4D+8pFH3rMePJj+AqxSY5kVmRu1zw8/XBi3ffvf39269ae5ruqDUScCRpiPAyPjxvXrX/ly7NiFwT//nDMF6aMJhvTx/Zw8eW0aV7/fU7PjCHgIAzhEVTu0NYdJnyh4JhAMgMG2KDCFN3dhpNop6cinfVmhBrUChFKDiFYQkVTAQ6CNvtSbdFQ4k0pBWNgMWwJWp2irYennhbayVPh1xuEBYLgztYYLJHm7xYuDeVcmGqeG91DBCIIiX1+pNiGhh4mAQCqX+xV37Rq2s7BQ9xA6puy0BWDSefMmn+cal3+ClrW89tojB4mpc3X69KULMzLyE2EmKqp+SBiXh87v3DlsM62PMxEm2bh69Z6sjRsPz8rOLhqPUzhwky9Xu92hhAYQgHlFQLAYmZqaE7bu3aN23LhRPBPawZo4OEbh7+9zi2vcal0A059Hj34l5PLlvIdxGxuoC+bBbS8HD47dRrRPQffukXvy8somIn02oEC3rc+l9KFOooMHly0dM+ZVO6E/Dd4PBhjYSBmUGRcX/U1LRpgxxooHVojpC62k6CunDFtIzZ9i6PzhCv+GWXfHa+ocPEYxUQmnYWhoaKqgScu8ZF9atoUyVnkbpDvqTJnMhyZcYaZpBd1iOsILI6TXacqhZWlMJov+o49esBEGdb6XZ56ZcGLnzhOqkpLyGJPJLGGXNEZHR6Q8++yEPVzjgR84LcASEOBveOKJxBPXrt0qKympUOt01aHIdMAYAQGysl69On1x4sTKlVzTcwKsgwbFlhMT5QTJk1VRUeWj0ejCzGaLhB1lxu+4TFQgsGvi42M2HTq0fDnXuK63YUkjoXd2//5zktLSis56vUGGmgDNn+Tke5bed1/8TbYuBMxnL1/OLSst1aorK6tDsd3wfFRUyEli77//1VevQPv1c+YkHSO+iLCkRNMJ6LOOMgCD0Hp3yJCeBZRvTDRAcPbq1TwNqZO6qqq2CX3ybvc/8MCwN7/6av4h3rt1Fwl1Jj8agVDTkBx7lAz6ApWUiWuIJmiyLJFoBSmV7kGUjp8Lm8xG6egonVoKRAUNBSpYx+mXWkP8OtP7UMdgD3VsCV1WI+i5pqev2ByOwziNAbeCx4M6FPPnf3r3+fPXB9y+XdafAOXQqlUvrCPMXMEwj50nmPDUF9mGDQeiL13KjXKGoJVy3RtvzLjINT01x8IIN9xwDQ8ikb3++pdDMzPze2q1NRElJbpobFBoqPJGr14dz61Z45ynU0MTu5KLPdzD2Y4PPtja/9ixywMJvXtsxFnq27fzxl273t7CTP3AKd4N5b/77sY4na5WSRjVsXLlc6e5xpN1TDjCTJ+VA/19+9JG3b5dHgf0e/fu9O2ePe9sZurlYOrkpP/SS6vvIT6ZiPgq1YsWTb9CaaOmbXbuETvfA8+bErthYgsikQDBwQMCe0ILnlnlykPnn7PFMZ0u4dp2oVCTOrsoqzUTgFjziD2Hy4HvhJ6hxp6PJuUaT8jBNrKr2xrOQoOjn5jjpdg+kfLqzLat4Rw1rvG0HPbMNB+mDmz/CtAUo3TwqKgGzeKCHrZDyviXKBiwLVYeoF3Vnz0PzcoEbqSM1mafNTLChp09K2bysO+WpW9m6mT3pBFcXqRjuT8u7y88TJC78yRL9qRM/gmZd5yOSc9A45+EyT9h084AoOFQPd5ZbSKGaUQ8IeVgtJyVAVTDIX0uDj7kn/jpYPKxc3sEHup/x+mdDODYxPHek60F9B2892vnmh7L6xoIrV0I8cfl7vrR+Zc9TJB3xCvH6zCOa+bM5F9yrrGLc5v5szJZxnF4cVStKxp35OedpdzkcvNsm9HnvFgF+X8CDAC+Ji9KXcEUJwAAAABJRU5ErkJggg%3D%3D";
	
	/**
	 * Displayed on the page.
	 * 
	 * @var string
	 */
	var $page_icon;
	
	/**
	 * (optional) The position in the menu order this menu should appear. 
	 * By default, if this parameter is omitted, the menu will appear at the bottom 
	 * of the menu structure. The higher the number, the lower its position in the 
	 * menu. WARNING: if 2 menu items use the same position attribute, one of the 
	 * items may be overwritten so that only one item displays!
	 * 
	 * Default: bottom of menu structure

	 * @var integer
	 */
	var $position = 90;
	
	/**
	 * The initial features supported by this administrative page.
	 * 
	 * @var array
	 */
	var $supports = array( 'revisions' );
	
	/**
	 * By setting a parent menu, then the menu link created by this page will
	 * become a child of that menu. To not set a parent menu then you will
	 * be creating a completely new menu.
	 * 
	 * @var string
	 */
	var $parent_menu;
	
	/**
	 * Add your screen option information
	 * 
	 * @var string
	 */
	var $screen_options;
	
	/**
	 * The number of columns to display on this administrative page.
	 * 
	 * 	1 	Full page width
	 * 	2 	Standard right sidebar
	 * 	2.5 50/50 columns
	 * 	3	Standard right column, and standard left column
	 * 
	 * @var integer
	 */
	var $columns = 2;
	
	/**
	 * Whether or not to display a list of custom posts
	 * 
	 * @var  object
	 */
	var $list = false;
	
	/**
	 * Upon instantiation of this class we determine if the current page is the
	 * page being created by this class. If that is true, then this variable is
	 * true.
	 * 
	 * @var boolean
	 */
	var $_isCurrent = false;
	
	/**
	 * Admin page settings are saved as custom post type metas for hidden posts,
	 * this is the name of that hidden post type
	 * 
	 * @var string
	 */
	var $_post_type;
	
	/**
	 * Setting this to true, WILL DELETE all of the settings and the revision history.
	 * 
	 * @var boolean
	 */
	var $_delete = false;
	
	/**
	 * Constructor
	 *
	 * @access	protected
	 */
	function __construct( $options = array() ) 
	{
		//initializing class
		$this->setProperties($options);
		
		//Setting the name of the post type to use
		if (is_a($this->_post_type, 'redrokk_post_class')) {
			$this->_post_type = $this->list->_post_type;
		}
		elseif (!$this->_post_type) {
			$this->_post_type = $this->id;
		}
	
		if (is_a($this->list, 'redrokk_post_class')) {
			$this->list = $this->list->_post_type;
		}
		
		if (!$this->page_icon) {
			$this->page_icon = $this->screen_icon;
		}
		
		global $screen_layout_columns;
		if (!$screen_layout_columns) {
			$screen_layout_columns = $this->columns;
		}
		
		//Determine if this is the current page displaying
		if ((isset($_GET['page']) && $_GET['page'] == $this->id) || (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == $this->id)) {
			$this->_isCurrent = true;
		}
		
		//HOOKS
		add_action('admin_menu', array($this, 'init_admin_menu'));
		add_filter('get_edit_post_link', array($this, 'filter_rewrite_edit_post_link'), 10, 3);
		
		//Displaying a custom listing type.
		//Beneficial when you don't want to create an entirely new menu for CLT's
		if ($this->list) {
			//initializing default variables for custom post type display
			if (!$this->columns) {
				$this->columns = 1;
			}
			
			add_action('plugins_loaded', array($this, 'init_secondary_menu'));
			
			if ($this->_isCurrent) {
				add_action('admin_head', array($this, 'init_admin_list'), 100);
				add_filter('screen_settings', array($this, 'getScreenSettings'));
			}
			
			return;
		}
		
		//Displaying a single post item
		if ($this->single) {
			if (isset($_GET['post']) || isset($_GET['post_ID']))
			{
				$this->_id = isset($_GET['post'])? $_GET['post'] :$_GET['post_ID'];
				$post = get_post( $this->_id );
				$this->_post_type = $post->post_type;
				
				if ($this->single != $post->post_type)
						return;
			}
			else
			{
				require_once ABSPATH.'wp-admin/includes/post.php';
				
				$this->_post_type = $this->single;
				$post = get_default_post_to_edit( $this->_post_type, true );
				$this->_id = $post->ID;
			}
			//required for saving singles
			add_action("admin_page_$this->id", array($this, 'getContext'));
		}
		else {
			add_action('init', array($this, 'init_post_type'));
		}
		
		add_action('save_post', array($this, 'save_post'));
		add_action('wp_restore_post_revision', array($this, 'save_revision_restore'), 20, 2);
		add_action('revision_table', array($this, 'getRevisionTable'), 20, 3);
		
		if ($this->_isCurrent) {
			add_action('admin_head', array($this, 'init_admin_settings'), 100);
			add_filter('screen_settings', array($this, 'getScreenSettings'));
		}
		else {
			add_action('admin_init', array($this, 'init_admin_settings_outside'));
		}
		
		//create the post for this page.
		$this->_id = get_option("post_id-$this->id", false);
		
		ob_start();
	}
	
	/**
	 * Initializing the required attributes for this page.
	 */
	function init_admin_list()
	{
		//initializing
		global $post, $action, $screen_layout_columns, $current_screen, $wp_list_table, 
				$post_type_object, $wp_query;
		
		$post_type = $this->list;
		$current_screen->id = $this->id;
		$current_screen->post_type = $post_type;
		$post_type_object = get_post_type_object($post_type);
		
		//checking permissions
		if ( ! $post_type_object )
			wp_die( __( 'Invalid post type' ) );
		
		if ( ! current_user_can( $post_type_object->cap->edit_posts ) )
			wp_die( __( 'Cheatin&#8217; uh?' ) );
		
		//loading the table class
		$wp_list_table = _get_list_table('WP_Posts_List_Table');
		$pagenum = $wp_list_table->get_pagenum();
		
		$doaction = $wp_list_table->current_action();
		if ( $doaction )
		{
			check_admin_referer('bulk-posts');
		
			$sendback = remove_query_arg( array('trashed', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
			if ( ! $sendback )
				$sendback = admin_url( $parent_file );
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );
			if ( strpos($sendback, 'post.php') !== false )
				$sendback = admin_url($post_new_file);
		
			if ( 'delete_all' == $doaction ) {
				$post_status = preg_replace('/[^a-z0-9_-]+/i', '', $_REQUEST['_post_status']);
				if ( get_post_status_object($post_status) ) // Check the post status exists first
					$post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", $post_type, $post_status ) );
				$doaction = 'delete';
			} elseif ( isset( $_REQUEST['media'] ) ) {
				$post_ids = $_REQUEST['media'];
			} elseif ( isset( $_REQUEST['ids'] ) ) {
				$post_ids = explode( ',', $_REQUEST['ids'] );
			} elseif ( !empty( $_REQUEST['post'] ) ) {
				$post_ids = array_map('intval', $_REQUEST['post']);
			}
		
			if ( !isset( $post_ids ) ) {
				wp_redirect( $sendback );
				exit;
			}
		
			switch ( $doaction ) {
				case 'trash':
					$trashed = 0;
					foreach( (array) $post_ids as $post_id ) {
						if ( !current_user_can($post_type_object->cap->delete_post, $post_id) )
							wp_die( __('You are not allowed to move this item to the Trash.') );
		
						if ( !wp_trash_post($post_id) )
							wp_die( __('Error in moving to Trash.') );
		
						$trashed++;
					}
					$sendback = add_query_arg( array('trashed' => $trashed, 'ids' => join(',', $post_ids) ), $sendback );
					break;
				case 'untrash':
					$untrashed = 0;
					foreach( (array) $post_ids as $post_id ) {
						if ( !current_user_can($post_type_object->cap->delete_post, $post_id) )
							wp_die( __('You are not allowed to restore this item from the Trash.') );
		
						if ( !wp_untrash_post($post_id) )
							wp_die( __('Error in restoring from Trash.') );
		
						$untrashed++;
					}
					$sendback = add_query_arg('untrashed', $untrashed, $sendback);
					break;
				case 'delete':
					$deleted = 0;
					foreach( (array) $post_ids as $post_id ) {
						$post_del = & get_post($post_id);
		
						if ( !current_user_can($post_type_object->cap->delete_post, $post_id) )
							wp_die( __('You are not allowed to delete this item.') );
		
						if ( $post_del->post_type == 'attachment' ) {
							if ( ! wp_delete_attachment($post_id) )
								wp_die( __('Error in deleting...') );
						} else {
							if ( !wp_delete_post($post_id) )
								wp_die( __('Error in deleting...') );
						}
						$deleted++;
					}
					$sendback = add_query_arg('deleted', $deleted, $sendback);
					break;
				case 'edit':
					if ( isset($_REQUEST['bulk_edit']) ) {
						$done = bulk_edit_posts($_REQUEST);
		
						if ( is_array($done) ) {
							$done['updated'] = count( $done['updated'] );
							$done['skipped'] = count( $done['skipped'] );
							$done['locked'] = count( $done['locked'] );
							$sendback = add_query_arg( $done, $sendback );
						}
					}
					break;
			}
		
			$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
		
			wp_redirect($sendback);
			exit();
		} elseif ( ! empty($_REQUEST['_wp_http_referer']) ) {
			wp_redirect( remove_query_arg( array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI']) ) );
			exit;
		}
		
		$_GET['post_type'] = $post_type;
		$wp_list_table->prepare_items();
		
		wp_enqueue_script('inline-edit-post');
		wp_enqueue_script('post');
		wp_enqueue_script('postbox');
		
		add_screen_option( 'per_page', array('label' => 'Listings per page', 'default' => 20) );
		
		get_current_screen()->add_help_tab( array(
				'id'      => 'plugin-support',
				'title'   => __('Plugin Support'),
				'content' => $this->getPluginSupport(),
			) );
			
		// All meta boxes should be defined and added before the first do_meta_boxes() call (or potentially during the do_meta_boxes action).
		require_once(ABSPATH.'/wp-admin/includes/meta-boxes.php');
		
		//add_meta_box($this->id.'_metabox_list', 'Listings', array($this, 'metabox_list'), $this->id, 'normal', 'core');
		
		do_action('add_meta_boxes', $this->id, $post);
		do_action('add_meta_boxes_' . $this->id, $post);
		
		do_action('do_meta_boxes', $this->id, 'normal', $post);
		do_action('do_meta_boxes', $this->id, 'advanced', $post);
		do_action('do_meta_boxes', $this->id, 'side', $post);
		return $this;
	}
	
	/**
	 * Initializing the required attributes for this page.
	 */
	function init_admin_settings()
	{
		//setting up the post params
		global $post, $action, $current_screen, $screen_layout_columns;
		$current_screen->id = $this->id;
		$post_ID = $this->_id;
		$post = get_post($post_ID);
		$post_type = $post->post_type;
		
		wp_reset_vars(array('action'));
		$action = isset($action) && $action ? $action : 'edit';
		
		if ($action !== 'editpost')
			ob_flush();
		
		switch($action) 
		{
		case 'postajaxpost':
		case 'post':
		case 'post-quickpress-publish':
		case 'post-quickpress-save':
			if ( 'post-quickpress-publish' == $action )
				$_POST['publish'] = 'publish'; // tell write_post() to publish
		
			if ( 'post-quickpress-publish' == $action || 'post-quickpress-save' == $action ) {
				$_POST['comment_status'] = 'closed';
				$_POST['ping_status'] = 'closed';
			}
		
			if ( !empty( $_POST['quickpress_post_ID'] ) ) {
				$_POST['post_ID'] = (int) $_POST['quickpress_post_ID'];
				$post_ID = edit_post();
			} else {
				$post_ID = 'postajaxpost' == $action ? edit_post() : write_post();
			}
		
			if ( 0 === strpos( $action, 'post-quickpress' ) ) {
				$_POST['post_ID'] = $post_ID;
				// output the quickpress dashboard widget
				require_once(ABSPATH . 'wp-admin/includes/dashboard.php');
				wp_dashboard_quick_press();
				exit;
			}
		
			redirect_post($post_ID);
			exit();
			break;
		
		default:
		case 'edit':
			
			add_screen_option('layout_columns', array(
					'max' => $screen_layout_columns, 
					'default' => $screen_layout_columns
				));
			
			get_current_screen()->add_help_tab( array(
					'id'      => 'plugin-support',
					'title'   => __('Plugin Support'),
					'content' => $this->getPluginSupport(),
				) );
			
			if (!$this->single)
			{
				get_current_screen()->add_help_tab( array(
						'id'      => 'post-revisions',
						'title'   => __('Post Revisions'),
						'content' => '<p>For the purpose of this plugin, I have enabled the storing of post meta data with revisions. You may restore to a previous point in history by using the Revisions meta box, listed near the bottom of this screen.</p>',
					) );
			}
			else 
			{
				$customize_display = '<p>' . __('The title field and the big Post Editing Area are fixed in place, but you can reposition all the other boxes using drag and drop, and can minimize or expand them by clicking the title bar of each box. Use the Screen Options tab to unhide more boxes (Excerpt, Send Trackbacks, Custom Fields, Discussion, Slug, Author) or to choose a 1- or 2-column layout for this screen.') . '</p>';
			
				get_current_screen()->add_help_tab( array(
					'id'      => 'customize-display',
					'title'   => __('Customizing This Display'),
					'content' => $customize_display,
				) );
			
				$title_and_editor  = '<p>' . __('<strong>Title</strong> - Enter a title for your post. After you enter a title, you&#8217;ll see the permalink below, which you can edit.') . '</p>';
				$title_and_editor .= '<p>' . __('<strong>Post editor</strong> - Enter the text for your post. There are two modes of editing: Visual and HTML. Choose the mode by clicking on the appropriate tab. Visual mode gives you a WYSIWYG editor. Click the last icon in the row to get a second row of controls. The HTML mode allows you to enter raw HTML along with your post text. You can insert media files by clicking the icons above the post editor and following the directions. You can go to the distraction-free writing screen via the Fullscreen icon in Visual mode (second to last in the top row) or the Fullscreen button in HTML mode (last in the row). Once there, you can make buttons visible by hovering over the top area. Exit Fullscreen back to the regular post editor.') . '</p>';
			
				get_current_screen()->add_help_tab( array(
					'id'      => 'title-post-editor',
					'title'   => __('Title and Post Editor'),
					'content' => $title_and_editor,
				) );
			
				$publish_box = '<p>' . __('<strong>Publish</strong> - You can set the terms of publishing your post in the Publish box. For Status, Visibility, and Publish (immediately), click on the Edit link to reveal more options. Visibility includes options for password-protecting a post or making it stay at the top of your blog indefinitely (sticky). Publish (immediately) allows you to set a future or past date and time, so you can schedule a post to be published in the future or backdate a post.') . '</p>';
			
				if ( current_theme_supports( 'post-formats' ) && post_type_supports( 'post', 'post-formats' ) ) {
					$publish_box .= '<p>' . __( '<strong>Post Format</strong> - This designates how your theme will display a specific post. For example, you could have a <em>standard</em> blog post with a title and paragraphs, or a short <em>aside</em> that omits the title and contains a short text blurb. Please refer to the Codex for <a href="http://codex.wordpress.org/Post_Formats#Supported_Formats">descriptions of each post format</a>. Your theme could enable all or some of 10 possible formats.' ) . '</p>';
				}
			
				if ( current_theme_supports( 'post-thumbnails' ) && post_type_supports( 'post', 'thumbnail' ) ) {
					$publish_box .= '<p>' . __('<strong>Featured Image</strong> - This allows you to associate an image with your post without inserting it. This is usually useful only if your theme makes use of the featured image as a post thumbnail on the home page, a custom header, etc.') . '</p>';
				}
			
				get_current_screen()->add_help_tab( array(
					'id'      => 'publish-box',
					'title'   => __('Publish Box'),
					'content' => $publish_box,
				) );
			
				$discussion_settings  = '<p>' . __('<strong>Send Trackbacks</strong> - Trackbacks are a way to notify legacy blog systems that you&#8217;ve linked to them. Enter the URL(s) you want to send trackbacks. If you link to other WordPress sites they&#8217;ll be notified automatically using pingbacks, and this field is unnecessary.') . '</p>';
				$discussion_settings .= '<p>' . __('<strong>Discussion</strong> - You can turn comments and pings on or off, and if there are comments on the post, you can see them here and moderate them.') . '</p>';
			
				get_current_screen()->add_help_tab( array(
					'id'      => 'discussion-settings',
					'title'   => __('Discussion Settings'),
					'content' => $discussion_settings,
				) );
				
			}
			
			add_thickbox();
			wp_enqueue_script('post');
			wp_enqueue_script('postbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('quicktags');
			
			if ( $last = wp_check_post_lock( $post->ID ) ) {
				add_action('admin_notices', '_admin_notice_post_locked' );
			} else {
				wp_set_post_lock( $post->ID );
				wp_enqueue_script('autosave');
			}
			
			// All meta boxes should be defined and added before the first do_meta_boxes() call (or potentially during the do_meta_boxes action).
			require_once(ABSPATH.'/wp-admin/includes/meta-boxes.php');
			
			if (!$this->single) {
				add_meta_box($this->id.'_metabox_publish', 'Save', array($this, 'metabox_publish'), $this->getContext(), 'side', 'core');
			}
			else {
				add_meta_box('submitdiv', __('Publish'), 'post_submit_meta_box', $this->getContext(), 'side', 'core');
				
				if ( !( 'pending' == $post->post_status && !current_user_can( $post_type_object->cap->publish_posts ) ) )
					add_meta_box('slugdiv', __('Slug'), 'post_slug_meta_box', $this->getContext(), 'normal', 'core');
				
			}
			
			if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post_type, 'post-formats' ) )
				add_meta_box( 'formatdiv', _x( 'Format', 'post format' ), 'post_format_meta_box', $this->getContext(), 'side', 'core' );
						
			if ( post_type_supports($post_type, 'page-attributes') )
				add_meta_box('pageparentdiv', __('Attributes'), 'page_attributes_meta_box', $this->getContext(), 'side', 'core');
						
			// all taxonomies
			foreach ( get_object_taxonomies($post_type) as $tax_name ) {
				$taxonomy = get_taxonomy($tax_name);
				if ( ! $taxonomy->show_ui )
					continue;
				
				$label = $taxonomy->labels->name;
				
				if ( !is_taxonomy_hierarchical($tax_name) )
					add_meta_box('tagsdiv-' . $tax_name, $label, 'post_tags_meta_box', $this->getContext(), 'side', 'core', array( 'taxonomy' => $tax_name ));
				else
					add_meta_box($tax_name . 'div', $label, 'post_categories_meta_box', $this->getContext(), 'side', 'core', array( 'taxonomy' => $tax_name ));
			}
			
			if ( current_theme_supports( 'post-thumbnails', $post_type ) && post_type_supports( $post_type, 'thumbnail' )
				&& ( ! is_multisite() || ( ( $mu_media_buttons = get_site_option( 'mu_media_buttons', array() ) ) && ! empty( $mu_media_buttons['image'] ) ) ) )
					add_meta_box('postimagediv', __('Featured Image'), 'post_thumbnail_meta_box', $this->getContext(), 'side', 'low');
			
			if ( post_type_supports($post_type, 'excerpt') )
				add_meta_box('postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', $this->getContext(), 'normal', 'core');
			
			if ( post_type_supports($post_type, 'trackbacks') )
				add_meta_box('trackbacksdiv', __('Send Trackbacks'), 'post_trackback_meta_box', $this->getContext(), 'normal', 'core');
							
			if ( post_type_supports($post_type, 'custom-fields') )
				add_meta_box('postcustom', __('Custom Fields'), 'post_custom_meta_box', $this->getContext(), 'normal', 'core');
						
			do_action('dbx_post_advanced');
			if ( post_type_supports($post_type, 'comments') )
				add_meta_box('commentstatusdiv', __('Discussion'), 'post_comment_status_meta_box', $this->getContext(), 'normal', 'core');
			
			if ( ('publish' == $post->post_status || 'private' == $post->post_status) && post_type_supports($post_type, 'comments') )
				add_meta_box('commentsdiv', __('Comments'), 'post_comment_meta_box', $this->getContext(), 'normal', 'core');
			
			if ( post_type_supports($post_type, 'author') ) {
				if ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) )
					add_meta_box('authordiv', __('Author'), 'post_author_meta_box', $this->getContext(), 'normal', 'core');
			}
						
			if ( post_type_supports($post_type, 'revisions') && 0 < $post_ID && wp_get_post_revisions( $post_ID ) )
				add_meta_box('revisionsdiv', __('Revisions'), 'post_revisions_meta_box', $this->getContext(), 'advanced', 'low');
		
			do_action('add_meta_boxes', $this->getContext(), $post);
			do_action('add_meta_boxes_' . $this->getContext(), $post);
			
			do_action('do_meta_boxes', $this->getContext(), 'normal', $post);
			do_action('do_meta_boxes', $this->getContext(), 'advanced', $post);
			do_action('do_meta_boxes', $this->getContext(), 'side', $post);
			break;
			
		case 'editattachment':
			check_admin_referer('update-attachment_' . $post_ID);
		
			// Don't let these be changed
			unset($_POST['guid']);
			$_POST['post_type'] = 'attachment';
		
			// Update the thumbnail filename
			$newmeta = wp_get_attachment_metadata( $post_ID, true );
			$newmeta['thumb'] = $_POST['thumb'];
		
			wp_update_attachment_metadata( $post_ID, $newmeta );
		
		case 'editpost':
			
			check_admin_referer('update-' . $post_type . '_' . $post_ID);
			
			$post_ID = edit_post();
			
			ob_clean();
			if ($this->single) {
				$url = add_query_arg( 'post', $post_ID, $this->getAdminUrl());
				wp_redirect($url);
			} else {
				wp_redirect( add_query_arg('message', '1', $this->getAdminUrl())); // Send user on their way while we keep working
			}
			exit();
			break;
					
		}
		return $this;
	}
	
	/**
	 * Function outputs the structure for an administrative listing of post
	 * types.
	 */
	function page_list()
	{
		//initializing
		global $action, $wp_list_table, $post_type_object;
		
		$post_type = $this->list;
		$post_type_object = get_post_type_object($post_type);
		
		?>
		<div class="wrap">
			<img src="<?php echo $this->page_icon; ?>" 
				style="margin:10px 20px 10px 0;position:relative;float:left;" />
				
			<h2><?php echo esc_html( $post_type_object->labels->name ); ?> <a href="<?php echo $post_new_file ?>" class="add-new-h2"><?php echo esc_html($post_type_object->labels->add_new); ?></a> <?php
			if ( isset($_REQUEST['s']) && $_REQUEST['s'] )
				printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', get_search_query() ); ?>
			</h2>
			<div class="clear"></div>
			
			<?php if ( isset($_REQUEST['locked']) || isset($_REQUEST['skipped']) || isset($_REQUEST['updated']) || isset($_REQUEST['deleted']) || isset($_REQUEST['trashed']) || isset($_REQUEST['untrashed']) ) {
				$messages = array();
			?>
			<div id="message" class="updated"><p>
			<?php if ( isset($_REQUEST['updated']) && (int) $_REQUEST['updated'] ) {
				$messages[] = sprintf( _n( '%s post updated.', '%s posts updated.', $_REQUEST['updated'] ), number_format_i18n( $_REQUEST['updated'] ) );
				unset($_REQUEST['updated']);
			}
			
			if ( isset($_REQUEST['skipped']) && (int) $_REQUEST['skipped'] )
				unset($_REQUEST['skipped']);
			
			if ( isset($_REQUEST['locked']) && (int) $_REQUEST['locked'] ) {
				$messages[] = sprintf( _n( '%s item not updated, somebody is editing it.', '%s items not updated, somebody is editing them.', $_REQUEST['locked'] ), number_format_i18n( $_REQUEST['locked'] ) );
				unset($_REQUEST['locked']);
			}
			
			if ( isset($_REQUEST['deleted']) && (int) $_REQUEST['deleted'] ) {
				$messages[] = sprintf( _n( 'Item permanently deleted.', '%s items permanently deleted.', $_REQUEST['deleted'] ), number_format_i18n( $_REQUEST['deleted'] ) );
				unset($_REQUEST['deleted']);
			}
			
			if ( isset($_REQUEST['trashed']) && (int) $_REQUEST['trashed'] ) {
				$messages[] = sprintf( _n( 'Item moved to the Trash.', '%s items moved to the Trash.', $_REQUEST['trashed'] ), number_format_i18n( $_REQUEST['trashed'] ) );
				$ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
				$messages[] = '<a href="' . esc_url( wp_nonce_url( "edit.php?post_type=$post_type&doaction=undo&action=untrash&ids=$ids", "bulk-posts" ) ) . '">' . __('Undo') . '</a>';
				unset($_REQUEST['trashed']);
			}
			
			if ( isset($_REQUEST['untrashed']) && (int) $_REQUEST['untrashed'] ) {
				$messages[] = sprintf( _n( 'Item restored from the Trash.', '%s items restored from the Trash.', $_REQUEST['untrashed'] ), number_format_i18n( $_REQUEST['untrashed'] ) );
				unset($_REQUEST['undeleted']);
			}
			
			if ( $messages )
				echo join( ' ', $messages );
			unset( $messages );
			
			$_SERVER['REQUEST_URI'] = remove_query_arg( array('locked', 'skipped', 'updated', 'deleted', 'trashed', 'untrashed'), $_SERVER['REQUEST_URI'] );
			?>
			</p></div>
			<?php } ?>
			
			<?php $wp_list_table->views(); ?>
			
			<form id="posts-filter" method="post" 
				action="<?php echo site_url('wp-admin/'.$this->getAdminUrl()); ?>">
				
				<?php $wp_list_table->search_box( $post_type_object->labels->search_items, 'post' ); ?>
				
				<input type="hidden" name="_post_status" class="post_status_page" value="<?php echo !empty($_REQUEST['post_status']) ? esc_attr($_REQUEST['post_status']) : 'all'; ?>" />
				<input type="hidden" name="_post_type" class="post_type_page" value="<?php echo $post_type; ?>" />
				<?php if ( ! empty( $_REQUEST['show_sticky'] ) ) { ?>
				<input type="hidden" name="_show_sticky" value="1" />
				<?php } ?>
				
				<?php $wp_list_table->display(); ?>
				
			</form>
			
			<?php
			if ( $wp_list_table->has_items() )
				$wp_list_table->inline_edit();
			?>
			
			<div id="ajax-response"></div>
			<br class="clear" />
		</div>

		<?php 
	}
	
	/**
	 * Function outputs the structure for an administrative settings page.
	 * 
	 */
	function page_settings()
	{
		global $current_screen,$screen_layout_columns,$notice,$action,$safe_mode,$withcomments,$posts,$content,$edited_post_title,$comment_error,$profile,$trackback_url,$excerpt,$showcomments,$commentstart,$commentend,$commentorder;
		wp_reset_vars(array('action', 'safe_mode', 'withcomments', 'posts', 'content', 'edited_post_title', 'comment_error', 'profile', 'trackback_url', 'excerpt', 'showcomments', 'commentstart', 'commentend', 'commentorder'));
		
		//setting up the post params
		$post_id = $this->_id;
		$post_ID = $post_id;
		$post = get_post($post_id);
		$post_type_object = get_post_type_object($post->post_type);
		$post_type = $post->post_type;
		
		$action = isset($action) ? $action : 'edit';
		
		$messages = array(
			0 => '', // Unused. Messages start at index 1.
			1 => 'Settings have been updated.',
			2 => 'Custom field updated.',
			3 => 'Custom field deleted.',
			4 => 'Settings have been updated.',
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Settings restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			7 => 'Settings saved.',
			9 => 'You have been redirect from this page: '.(isset($_GET['redreferrer'])?$_GET['redreferrer']:'Unknown'),
		);
		
		$message = false;
		if ( isset($_GET['message']) ) {
			$_GET['message'] = absint( $_GET['message'] );
			if ( isset($messages[$_GET['message']]) )
				$message = $messages[$_GET['message']];
		}
		
		$form_extra = '';
		if ( 'auto-draft' == $post->post_status ) {
			if ( 'edit' == $action )
				$post->post_title = '';
			$autosave = false;
			$form_extra .= "<input type='hidden' id='auto_draft' name='auto_draft' value='1' />";
		} else {
			$autosave = wp_get_post_autosave( $post_ID );
		}
		
		$form_action = 'editpost';
		$nonce_action = 'update-' . $post_type . '_' . $post_ID;
		$form_extra .= "<input type='hidden' id='post_ID' name='post_ID' value='" . esc_attr($post_ID) . "' />";
		
		// Detect if there exists an autosave newer than the post and if that autosave is different than the post
		if ( $autosave && mysql2date( 'U', $autosave->post_modified_gmt, false ) > mysql2date( 'U', $post->post_modified_gmt, false ) ) {
			foreach ( _wp_post_revision_fields() as $autosave_field => $_autosave_field ) {
				if ( normalize_whitespace( $autosave->$autosave_field ) != normalize_whitespace( $post->$autosave_field ) ) {
					$notice = sprintf( __( 'There is an autosave of these settings that is more recent than the version below.  <a href="%s">View the autosave</a>' ), add_query_arg( 'redpost_id', $autosave->ID, $this->getAdminUrl() ) );
					break;
				}
			}
			unset($autosave_field, $_autosave_field);
		}
		
		?>
<div class="wrap">
<img src="<?php echo $this->page_icon; ?>" 
	style="margin:10px 20px 10px 0;position:relative;float:left;" />
<h2 style="line-height:48px;"><?php echo esc_html( $this->page_title ); ?></h2>
<div class="clear"></div>

<?php if ( isset($notice) && $notice ) : ?>
	<div id="notice" class="error"><p><?php echo $notice; ?></p></div>
<?php endif; ?>
<?php if ( $message ) : ?>
	<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>

<form name="form<?php echo $this->id ?>" action="options.php" method="post" 
	id="<?php echo $this->id ?>">
	
	<?php wp_nonce_field($nonce_action); ?>
	<input type="hidden" id="user-id" name="user_ID" value="<?php echo get_current_user_ID() ?>" />
	<input type="hidden" id="hiddenaction" name="action" value="<?php echo esc_attr( $form_action ) ?>" />
	<input type="hidden" id="originalaction" name="originalaction" value="<?php echo esc_attr( $form_action ) ?>" />
	<input type="hidden" id="post_author" name="post_author" value="<?php echo esc_attr( $post->post_author ); ?>" />
	<input type="hidden" id="post_type" name="post_type" value="<?php echo esc_attr( $post_type ) ?>" />
	<input type="hidden" id="original_post_status" name="original_post_status" value="<?php echo esc_attr( $post->post_status) ?>" />
	<input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(stripslashes(wp_get_referer())); ?>" />
	<?php if ( ! empty( $active_post_lock ) ) { ?>
	<input type="hidden" id="active_post_lock" value="<?php echo esc_attr( implode( ':', $active_post_lock ) ); ?>" />
	<?php
	}
	
	if ( 'draft' != $post->post_status )
		wp_original_referer_field(true, 'previous');
				
	echo $form_extra;
	
	wp_nonce_field( 'autosave', 'autosavenonce', false );
	wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
	wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
	?>
	
	<div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">
		<div id="side-info-column" class="inner-sidebar">
			<?php 
			if ( 1 < $screen_layout_columns ) {
				do_meta_boxes($this->getContext(), 'side', $post);
			}
			?>
		</div>
		
		<div id="post-body">
			<div id="post-body-content">
				<?php if ( post_type_supports($post_type, 'title') ) { ?>
				<div id="titlediv">
					<div id="titlewrap">
						<label class="hide-if-no-js" style="visibility:hidden" id="title-prompt-text" for="title"><?php echo apply_filters( 'enter_title_here', __( 'Enter title here' ), $post ); ?></label>
						<input type="text" name="post_title" size="30" tabindex="1" value="<?php echo esc_attr( htmlspecialchars( $post->post_title ) ); ?>" id="title" autocomplete="off" />
					</div>
					<div class="inside">
					<?php
					$sample_permalink_html = $post_type_object->public ? get_sample_permalink_html($post->ID) : '';
					$shortlink = wp_get_shortlink($post->ID, 'post');
					if ( !empty($shortlink) )
					    $sample_permalink_html .= '<input id="shortlink" type="hidden" value="' . esc_attr($shortlink) . '" /><a href="#" class="button" onclick="prompt(&#39;URL:&#39;, jQuery(\'#shortlink\').val()); return false;">' . __('Get Shortlink') . '</a>';
					
					if ( $post_type_object->public && ! ( 'pending' == $post->post_status && !current_user_can( $post_type_object->cap->publish_posts ) ) ) { ?>
						<div id="edit-slug-box">
						<?php
							if ( ! empty($post->ID) && ! empty($sample_permalink_html) && 'auto-draft' != $post->post_status )
								echo $sample_permalink_html;
						?>
						</div>
					<?php
					}
					?>
					</div>
					<?php wp_nonce_field( 'samplepermalink', 'samplepermalinknonce', false ); ?>
				</div>
				<?php } else { ?>
					<input type="hidden" name="post_title" id="title"
						value="<?php echo esc_attr( htmlspecialchars( $post->post_title ) ); ?>">
				<?php } ?>
				
				<?php if ( post_type_supports($post_type, 'editor') ) { ?>
				<div id="postdivrich" class="postarea">
				
				<?php wp_editor($post->post_content, 'content', array('dfw' => true, 'tabindex' => 1) ); ?>
				
				<table id="post-status-info" cellspacing="0"><tbody><tr>
					<td id="wp-word-count"><?php printf( __( 'Word count: %s' ), '<span class="word-count">0</span>' ); ?></td>
					<td class="autosave-info">
					<span class="autosave-message">&nbsp;</span>
				<?php
					if ( 'auto-draft' != $post->post_status ) {
						echo '<span id="last-edit">';
						if ( $last_id = get_post_meta($post_ID, '_edit_last', true) ) {
							$last_user = get_userdata($last_id);
							printf(__('Last edited by %1$s on %2$s at %3$s'), esc_html( $last_user->display_name ), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
						} else {
							printf(__('Last edited on %1$s at %2$s'), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
						}
						echo '</span>';
					} ?>
					</td>
				</tr></tbody></table>
				
				</div>
				<?php
				}
				?>
				<style>#post-body-content div#normal-sortables{min-height:0px;}</style>
				<?php 
				
				remove_meta_box('wpseo_meta', $this->id, 'normal');
				
				do_action('edit_form_high');
				do_meta_boxes($this->getContext(), 'high', $post); 
				
				do_action('edit_form_normal');
				do_meta_boxes($this->getContext(), 'normal', $post); 
				
				do_action('edit_form_advanced');
				do_meta_boxes($this->getContext(), 'advanced', $post);
				
				do_action('dbx_post_sidebar');
				if ( 1 == $screen_layout_columns ) {
					do_action('submitpost_box');
					do_meta_boxes($this->getContext(), 'side', $post);
				}
				
				?>
				<div id="edit-slug-box" style="visibility:hidden;"></div>
				
			</div>
		</div>
		<br class="clear" />
	</div><!-- /poststuff -->
</form>
</div>
<?php
if ( post_type_supports( $post_type, 'comments' ) )
	wp_comment_reply();
?>

<?php if ((isset($post->post_title) && '' == $post->post_title) || (isset($_GET['message']) && 2 > $_GET['message'])) : ?>
<script type="text/javascript">
try{document.post.title.focus();}catch(e){}
</script>
<?php endif; ?>

		<?php 
		return;
	}
	
	/**
	 * Display post submit form fields.
	 * 
	 * @param object $post
	 */
	function metabox_publish( $post )
	{
		//initializing
		global $action;

		$post_type = $post->post_type;
		$post_type_object = get_post_type_object($post_type);
		$can_publish = current_user_can($post_type_object->cap->publish_posts);
		
		?>
		<style>
		#adminsubscription_metabox_publish .inside {padding:0px;}
		</style>
		<div id="submitdiv">
		<div class="submitbox" id="submitpost">
			<div id="minor-publishing">
				<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
				<div style="display:none;">
					<?php submit_button( __( 'Save' ), 'button', 'save' ); ?>
				</div>
			
				<div id="minor-publishing-actions">
					<div id="save-action">
						<?php if ( 'publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status )  { ?>
							<input <?php if ( 'private' == $post->post_status ) { ?>style="display:none"<?php } ?> type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save Draft'); ?>" tabindex="4" class="button button-highlighted" />
						<?php } elseif ( 'pending' == $post->post_status && $can_publish ) { ?>
							<input type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save as Pending'); ?>" tabindex="4" class="button button-highlighted" />
						<?php } ?>
						<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="draft-ajax-loading" alt="" />
					</div>
					
					<div class="clear"></div>
				</div>
				<div id="misc-publishing-actions">
					<div class="misc-pub-section<?php if ( !$can_publish ) { echo ' misc-pub-section-last'; } ?>"><label for="post_status"><?php _e('Status:') ?></label>
						<span id="post-status-display">
						<?php
						switch ( $post->post_status ) {
							case 'private':
								_e('Privately Published');
								break;
							case 'publish':
								_e('Published');
								break;
							case 'future':
								_e('Scheduled');
								break;
							case 'pending':
								_e('Pending Review');
								break;
							case 'draft':
							case 'auto-draft':
								_e('Draft');
								break;
						}
						?>
						</span>
						<?php if ( 'publish' == $post->post_status || 'private' == $post->post_status || $can_publish ) { ?>
						<a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js" tabindex='4'><?php _e('Edit') ?></a>
						
						<div id="post-status-select" class="hide-if-js">
						<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('auto-draft' == $post->post_status ) ? 'draft' : $post->post_status); ?>" />
						<select name='post_status' id='post_status' tabindex='4'>
						<?php if ( 'publish' == $post->post_status ) : ?>
						<option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php _e('Published') ?></option>
						<?php elseif ( 'private' == $post->post_status ) : ?>
						<option<?php selected( $post->post_status, 'private' ); ?> value='publish'><?php _e('Privately Published') ?></option>
						<?php elseif ( 'future' == $post->post_status ) : ?>
						<option<?php selected( $post->post_status, 'future' ); ?> value='future'><?php _e('Scheduled') ?></option>
						<?php endif; ?>
						<option<?php selected( $post->post_status, 'pending' ); ?> value='pending'><?php _e('Pending Review') ?></option>
						
						<?php if ($this->getLastRevision()) : ?>
							<?php if ( 'auto-draft' == $post->post_status ) : ?>
							<option<?php selected( $post->post_status, 'auto-draft' ); ?> value='draft'><?php _e('Draft') ?></option>
							<?php else : ?>
							<option<?php selected( $post->post_status, 'draft' ); ?> value='draft'><?php _e('Draft') ?></option>
							<?php endif; ?>
						<?php endif; ?>
						
						</select>
						 <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
						 <a href="#post_status" class="cancel-post-status hide-if-no-js"><?php _e('Cancel'); ?></a>
						</div>
						
						<?php } ?>
					</div><?php // /misc-pub-section ?>
					
					
					<?php
					// translators: Publish box date formt, see http://php.net/date
					$datef = __( 'M j, Y @ G:i' );
					if ( 0 != $post->ID ) {
						if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
							$stamp = __('Scheduled for: <b>%1$s</b>');
						} else if ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
							$stamp = __('Published on: <b>%1$s</b>');
						} else if ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
							$stamp = __('Publish <b>immediately</b>');
						} else if ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
							$stamp = __('Schedule for: <b>%1$s</b>');
						} else { // draft, 1 or more saves, date specified
							$stamp = __('Publish on: <b>%1$s</b>');
						}
						$date = date_i18n( $datef, strtotime( $post->post_date ) );
					} else { // draft (no saves, and thus no date specified)
						$stamp = __('Publish <b>immediately</b>');
						$date = date_i18n( $datef, strtotime( current_time('mysql') ) );
					}
					
					if ( $can_publish ) : // Contributors don't get to choose the date of publish ?>
					<div class="misc-pub-section curtime misc-pub-section-last" style="position:absolute;opacity:0;margin-top:-1000px;">
						<span id="timestamp">
						<?php printf($stamp, $date); ?></span>
						<a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" tabindex='4'><?php _e('Edit') ?></a>
						<div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'),1,4); ?></div>
					</div><?php // /misc-pub-section ?>
					<?php endif; ?>
					
					<div class="misc-pub-section" class="autosave-info" style="text-align: left;">
						<label>Auto Save:</label>
						<b><span class="autosave-message">&nbsp;</span></b>
					</div>
					<div class="misc-pub-section misc-pub-section-last">
						<?php
						if ( 'auto-draft' != $post->post_status ) {
							echo '<span id="last-edit">';
							if ( $last_id = get_post_meta($post->ID, '_edit_last', true) ) {
								$last_user = get_userdata($last_id);
								printf(__('Last edited by %1$s on %2$s at %3$s'), esc_html( $last_user->display_name ), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
							} else {
								printf(__('Last edited on %1$s at %2$s'), mysql2date(get_option('date_format'), $post->post_modified), mysql2date(get_option('time_format'), $post->post_modified));
							}
							echo '</span>';
						}
						?>
					</div>
					
					<?php do_action('post_submitbox_misc_actions'); ?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div id="major-publishing-actions">
				<?php do_action('post_submitbox_start'); ?>
				<div id="publishing-action">
					<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo esc_attr( ('auto-draft' == $post->post_status ) ? 'draft' : $post->post_status); ?>" />
				
					<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="ajax-loading" alt="" />
					
					<?php
					if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
						if ( $can_publish ) :
							if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
							<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Schedule') ?>" />
							<?php submit_button( __( 'Schedule' ), 'primary', 'publish', false, array( 'tabindex' => '5', 'accesskey' => 'p' ) ); ?>
					<?php	else : ?>
							<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
							<?php submit_button( __( 'Publish' ), 'primary', 'publish', false, array( 'tabindex' => '5', 'accesskey' => 'p' ) ); ?>
					<?php	endif;
						else : ?>
							<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Submit for Review') ?>" />
							<?php submit_button( __( 'Submit for Review' ), 'primary', 'publish', false, array( 'tabindex' => '5', 'accesskey' => 'p' ) ); ?>
					<?php
						endif;
					} else { ?>
							<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update') ?>" />
							<input name="save" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="<?php esc_attr_e('Update') ?>" />
					<?php
					} ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		</div>
		<?php
	}
	
	/**
	 * Creates the private custom post type
	 */
	function init_post_type()
	{
		if (!post_type_exists($this->_post_type)) {
			register_post_type($this->_post_type, array(
				'public' => false,
				'query_var' => false,
				'rewrite' => false,
				'supports' => $this->supports,
				'labels' => array('name' => 'Redrokk Admin Settings'),
			));
		}
		
		//a little option to start my tests over
		if ($this->_delete) 
		{
			require_once ABSPATH.'wp-includes/pluggable.php';
			wp_delete_post($this->_id, true);
			delete_option("post_id-$this->id");
		}
		
		//prevent any errors by removing the post completely if it's a bad record
		if (!get_post($this->_id)) {
			delete_option("post_id-$this->id");
			$this->_id = false;
		}
		
		if (!$this->_id) {
			$this->_id = wp_insert_post(array(
				'comment_status' => 'closed', // 'closed' means no comments.
				'ping_status' => 'closed', // 'closed' means pingbacks or trackbacks turned off
				'post_author' => 0, //The user ID number of the author.
				'post_name' => $this->id, // The name (slug) for your post
				'post_status' => 'publish', //Set the status of the new post. 
				'post_title' => $this->page_title, //The title of your post.
				'post_type' => $this->_post_type //You may want to insert a regular post, page, link, a menu item or some custom post type
			));
			if (!add_option( "post_id-$this->id", $this->_id )) {
				update_option("post_id-$this->id", $this->_id);
			}
		}
		
		//Preparing for an autosave
		if (isset($_GET['redpost_id']) && $_GET['redpost_id']) {
			$this->_id = $_GET['redpost_id'];
		}
	}
	
	/**
	 * Returns the post that we're saving information to
	 * 
	 * @return mixed
	 */
	function getPost()
	{
		return get_post($this->_id);
	}
	
	/**
	 * This is a tiny contribution to our cause, I thank you for leaving this intact.
	 * Please add your own support blurb, in addition to ours as you see fit.
	 * 
	 * @return string
	 */
	function getPluginSupport()
	{
		// initializing
		$title = "RedRokk Interactive Studio &copy;";
		$descriptionencode = urlencode($description);
		
		$description = "The WordPress Total Widget Control Plugin is an amazing plugin!";
		$descriptionencode = urlencode($description);
		
		$url = 'http://www.redrokk.com';
		$urlencode = urlencode($url);
		
		ob_start();
		?>
		<h2>Plugin Support</h2>
		
		<div style="width: 341px;position: relative;float: right;margin: 0 30px 60px;border: 3px dashed #CCC;padding: 10px;">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="PL6N9MA68B5GG">
				<input type="hidden" name="on0" value="Support Level">
				<select name="os0">
					<option value="Email Support">Email Support : $19.99 USD - monthly</option>
					<option value="Video and Desktop Conferencing">Video and Desktop Conferencing : $79.99 USD - monthly</option>
				</select>
				<input type="hidden" name="currency_code" value="USD">
				<input type="image" src="http://assets.redrokk.com/buy-now.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
		
		<div style="font-size: 15px;line-height: 19px;">
			<p><b>Having problems with a plugin?</b> Redrokk Interactive Studios specializes in plugin development
			for all of the popular content management systems. From comprehensive email support to one-on-one screencasting tutorials right on your website, RedRokk
			is available to assist you.</p>
			
			<ol>
				<li style="margin-bottom: 20px;list-style-type:decimal">Click the Facebook Like Button</li>
				<li style="margin-bottom: 20px;list-style-type:decimal">Select your needed support level from the dropdown <br/>in the top right.</li>
				<li style="list-style-type:decimal">Click Order Now!</li>
			</ol>
		</div>
		
		<hr style="margin: 20px 0 10px;border: none;border-bottom: 1px dashed #CCC;width:100%;clear:both"/>
		<div style="position:relative;float:right;width:200px;">
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $urlencode ?>&amp;layout=box_count&amp;show_faces=false&amp;width=50&amp;action=like&amp;colorscheme=light&amp;height=65" 
				scrolling="no"
				frameborder="0"
				style="border:none; overflow:hidden; width:50px; height:65px;margin-bottom: -5px;"
				allowTransparency="true" ></iframe>
			
			<a href="http://twitter.com/share" class="twitter-share-button" 
				data-url="<?php echo $url ?>" 
				data-text="<?php echo $description ?>" 
				data-count="vertical" 
				data-via="jonathonbyrd">
					Tweet</a>
			
			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			
			<a href="http://digg.com/submit?url=<?php echo $urlencode ?>&bodytext=Total%20Widget%20Control%20allows%20you%20to%20customize%20any%20and%20every%20page%20of%20your%20WordPress%20website."
				class="DiggThisButton DiggMedium">
				<img src="http://developers.diggstatic.com/sites/all/themes/about/img/digg-btn.jpg" 
				alt="Digg <?php echo $title; ?>" 
				title="Digg <?php echo $title; ?>" />
					<?php echo $title; ?></a>
			
			<script type="text/javascript">
			(function() {
			var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
			s.type = 'text/javascript';
			s.async = true;
			s.src = 'http://widgets.digg.com/buttons.js';
			s1.parentNode.insertBefore(s, s1);
			})();
			</script>
		</div>
		<a href="<?php echo $url; ?>" style="text-decoration:none;">
			<img src="<?php echo $this->screen_icon; ?>" 
				height="41px" width="194px"
				style="margin:30px 20px 30px 0;position:relative;float:left;" />
			
			<h4 style="margin:0px;"><?php echo $title; ?></h4>
		</a>
		<p>
			<?php echo $title; ?> is a software development firm that accepts
			projects of all sizes. We contribute back to the community as much
			as possible by providing amazing plugins with exceptional API's for
			developers.
		</p>
		<?php 
		return ob_get_clean();
	}
	
	/**
	 * This is a tiny contribution to our cause, I thank you for leaving this intact.
	 * Please add your own support blurb, in addition to ours as you see fit.
	 * 
	 * @return string
	 */
	function getScreenSettings()
	{
		return $this->screen_options;
	}
	
	/**
	 * Returns the context to register metaboxes for
	 */
	function getContext()
	{
		if ($this->single)
			return $this->single;
		return $this->id;
	}
	
	/**
	 * Function registers the admin menu link
	 */
	function init_admin_menu()
	{
		// initializing
		if ($this->callback === NULL) {
			if ($this->list) {
				$this->callback = array($this, 'page_list');
			}
			else {
				$this->callback = array($this, 'page_settings');
			}
		}
		
		if ($this->menu_title === NULL) {
			$this->menu_title = $this->page_title;
		}
		
		// creating a child menu item
		if ($this->parent_menu) 
		{
			$parent_menu = $this->parent_menu;
			if (is_a($this->parent_menu, 'redrokk_admin_class')) {
				$parent_menu = $this->parent_menu->id;
			}
			
			$psuedo = array(
				'dashboard'	=> 'index.php',
				'posts'		=> 'edit.php',
				'media'		=> 'upload.php',
				'links'		=> 'link-manager.php',
				'pages'		=> 'edit.php?post_type=page',
				'comments'	=> 'edit-comments.php',
				'appearance'=> 'themes.php',
				'themes'	=> 'themes.php',
				'plugins'	=> 'plugins.php',
				'users'		=> 'users.php',
				'tools'		=> 'tools.php',
				'settings'	=> 'options-general.php',
			);
			if (isset($psuedo[$parent_menu])) {
				$parent_menu = $psuedo[$parent_menu];
			}
			add_submenu_page( $parent_menu, $this->page_title, $this->menu_title, $this->capability, 
				$this->id, $this->callback );
		}
		else 
		{
			//creating a new menu
			add_menu_page( $this->page_title, $this->menu_title, $this->capability, 
				$this->id, $this->callback, $this->icon_url, $this->position );
		}
	}
	
	/**
	 * Method displays the secondary link for custom listing types.
	 * 
	 */
	function init_secondary_menu()
	{
		$parent_menu = $this;
		if (is_a($this->parent_menu, 'redrokk_admin_class')) {
			$parent_menu = $this->parent_menu;
		}
		
		redrokk_admin_class::getInstance("{$this->list}_single", array(
			'page_title'	=> "New $this->page_title",
			'parent_menu'	=> $parent_menu,
			'single'		=> $this->list,
			));
	}
	
	/**
	 * Function makes sure that any links within the system are pointed to the proper
	 * settings page and not a core wordpress post edit page.
	 * 
	 * @param string $link
	 * @param integer $post_id
	 * @param string $context
	 */
	function filter_rewrite_edit_post_link( $link, $post_id, $context )
	{
		if ( !$post = &get_post( $this->_id ) )
				return $link;
		
		if ( 'display' == $context )
			$action = '&amp;action=edit';
		else
			$action = '&action=edit';
		
		$post_type_object = get_post_type_object( $post->post_type );
		if ( !$post_type_object )
				return $link;
		
		//post type listings rewrite
		$post = get_post($post_id);
		
		if ($this->list == $post->post_type)
		{
			$query = wp_parse_args( str_replace(array('&amp;','?'), '&', $link) );
			$child = redrokk_admin_class::getInstance("{$this->list}_single");
			$url = add_query_arg( 'post', $query['post'], $child->getAdminUrl());
			$link = site_url("wp-admin/$url");
		}
		//settings pages rewrite
		elseif ($link == admin_url( sprintf($post_type_object->_edit_link.$action, $this->_id) )) {
			return $this->getAdminUrl();
		}
		return $link;
	}
	
	/**
	 * Function is declared outside of our current page
	 */
	function init_admin_settings_outside()
	{
		if (defined('DOING_AJAX') && DOING_AJAX)
				return false;
		
		if ( isset($_GET['post']) )
			$post_id = (int) $_GET['post'];
		elseif ( isset($_POST['post_ID']) )
			$post_id = (int) $_POST['post_ID'];
		else
			$post_id = 0;
			
		if (!$post = get_post($post_id))
				return false;
				
		if ($post->post_type != $this->_post_type)
				return false;
		
		$url = add_query_arg( 'message', 9, $this->getAdminUrl());
		$url = add_query_arg( 'redreferrer', urlencode(wp_get_referer()), $url);
		wp_redirect( $url );
		exit;
	}
	
	/**
	 * Function saves the post meta's against their revisions.
	 * 
	 * @param string $post_id
	 */
	function save_post( $post_id )
	{
		// verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
				return;
				
		global $action, $wpdb;
		if ( $action == 'restore' ) 
				return;
		
		// Check permissions
		if ( !current_user_can( 'edit_post', $post_id ) )
				return;
		
		if ( $parent_id = wp_is_post_revision( $post_id ) )
		{
			$parent = get_post( $parent_id );
			$customs = get_post_custom($parent->ID);
			
			foreach ($customs as $property => $values)
			{
				foreach ($values as $value)
				{
					if (!update_metadata( 'post', $post_id, $property, $value )) {
						continue;
					}
				}
			}
		}
	}
	
	/**
	 * Function restores the saved post meta from the revision history
	 * back to its original post.
	 * 
	 * @param string $post_id
	 * @param string $revision_id
	 */
	function save_revision_restore( $post_id, $revision_id )
	{
		$customs = $this->getRevisionMeta($revision_id);
		foreach ($customs as $property => $values)
		{
			delete_metadata( 'post', $post_id, $property );
			foreach ($values as $value)
			{
				update_metadata( 'post', $post_id, $property, $value );
			}
		}
	}
	
	/**
	 * Function for grabbing the revision's meta data from the database
	 * and returning it as a proper meta_custom array.
	 * 
	 * @param string $object_id
	 */
	function getRevisionMeta( $object_id )
	{
		//initializing
		global $wpdb;
		$table = _get_meta_table('post');
		$customs = array();
		
		$results = $wpdb->get_results("SELECT * FROM $table WHERE post_id = $object_id");
		foreach ($results as $key => $meta)
		{
			if ( !isset($customs[$meta->meta_key]) || !is_array($customs[$meta->meta_key]) )
				$customs[$meta->meta_key] = array();
			
			$customs[$meta->meta_key][] = $meta->meta_value;
			array_map('maybe_unserialize', $customs[$meta->meta_key]);
		}
		
		return $customs;
	}
	
	/**
	 * Method returns the property value
	 * 
	 * @param string $property
	 * @param string $default
	 * @param boolean $single
	 */
	function getOption( $property, $default = false, $single = true )
	{
		if ($value = get_post_meta($this->_id, $property, $single))
			return $value;
			
		return $default;
	}
	
	/**
	 * Table only designed for testing
	 */
	function getRevisionTable($revision_id, $post_id, $action)
	{
		$customs = $this->getRevisionMeta( $revision_id );
		echo '<tr><td colspan=100>';
		
		echo '<h3>';print_r($revision_id);echo '</h3>';
		echo '<pre>';print_r($customs);echo '</pre>';
		
		echo '</td></tr>';
	}
	
	/**
	 * Returns the url for this administrative page
	 */
	function getAdminUrl()
	{
		return add_query_arg( 'page', $this->id, $this->getAdminParentUrl() );
	}
	
	/**
	 * Returns the url for this administrative page
	 */
	function getAdminParentUrl()
	{
		if (!$this->parent_menu) return 'admin.php?page='.$this->id;
		
		//if this is a child of this class
		if (is_string($this->parent_menu)) {
			$parent_menu = strtolower($this->parent_menu);
			
			//determining the page type
			$ignores = array('post','media');
			if (post_type_exists( $parent_menu ) && !in_array($parent_menu, $ignores))
				$parent_menu = 'post_type';
			
		}
		elseif (is_a($this->parent_menu, 'redrokk_admin_class')) {
			$parent_menu = $this->parent_menu->id;
		}
		
		switch ( $parent_menu )
		{
			case 'dashboard':	$p = 'index.php';	break;
			case 'post':
			case 'posts':		$p = 'edit.php';	break;
			case 'media':		$p = 'upload.php';	break;
			case 'links':		$p = 'link-manager.php';	break;
			case 'comments':	$p = 'edit-comments.php';	break;
			case 'appearance':	$p = 'themes.php';	break;
			case 'plugins':		$p = 'plugins.php';	break;
			case 'users':		$p = 'users.php';	break;
			case 'tools':		$p = 'tools.php';	break;
			case 'settings':	$p = 'options-general.php';	break;
			case 'page':
			case 'post_type':	$p = "edit.php?post_type=$this->parent_menu";	break;
			
			//if there's a custom menu id, then let them at it
			default:			$p = 'admin.php?page='.$parent_menu;	break; 
		}
		
		return site_url("/wp-admin/$p");
	}
	
	/**
	 * 
	 * @return string
	 */
	function getLastRevision( $status = 'publish', $autodraft = false )
	{
		global $post, $wpdb;
		
		$query  = "SELECT * FROM $wpdb->posts `wposts` WHERE `wposts`.`post_parent` = '$post->ID'"
				. " AND `wposts`.`post_type`='revision'";
		
		if ($status) $query .= " AND `wposts`.`post_status`='$status'";
		if ($autodraft) $query .= " AND `wposts`.`post_title` <> 'Auto Draft' AND `wposts`.`post_name` NOT LIKE '%autosave%'"; 
		
		$query  .= " ORDER BY `wposts`.`post_modified` DESC LIMIT 1";
		
		$results = $wpdb->get_results($query);
		
		if (isset($results[0]))
			return $results[0];
		return false;
	}

	/**
	 * Get the current page url
	 */
	function getCurrentPage()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src	 An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link	http://docs.joomla.org/JTable/bind
	 * @since   11.1
	 */
	public function bind($src, $ignore = array())
	{
		// If the source value is not an array or object return false.
		if (!is_object($src) && !is_array($src))
		{
			trigger_error('Bind failed as the provided source is not an array.');
			return $this;
		}

		// If the source value is an object, get its accessible properties.
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}

		// If the ignore value is a string, explode it over spaces.
		if (!is_array($ignore))
		{
			$ignore = explode(' ', $ignore);
		}

		// Bind the source value, excluding the ignored fields.
		foreach ($this->getProperties() as $k => $v)
		{
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore))
			{
				if (isset($src[$k]))
				{
					$this->$k = $src[$k];
				}
			}
		}

		return $this;
	}

	/**
	 * Set the object properties based on a named array/hash.
	 *
	 * @param   mixed  $properties  Either an associative array or another object.
	 *
	 * @return  boolean
	 *
	 * @since   11.1
	 *
	 * @see	 set() 
	 */
	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}
		}

		return $this;
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value	 The value of the property to set.
	 *
	 * @return  mixed  Previous value of the property.
	 *
	 * @since   11.1
	 */
	public function set($property, $value = null)
	{
		$_property = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
		if (method_exists($this, $_property)) {
			return $this->$_property($value);
		}

		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $this;
	}
	
	/**
	 * Returns an associative array of object properties.
	 *
	 * @param   boolean  $public  If true, returns only the public properties.
	 *
	 * @return  array 
	 *
	 * @see	 get()
	 */
	public function getProperties($public = true)
	{
		$vars = get_object_vars($this);
		if ($public)
		{
			foreach ($vars as $key => $value)
			{
				if ('_' == substr($key, 0, 1))
				{
					unset($vars[$key]);
				}
			}
		}

		return $vars;
	}

	/**
	 * 
	 * contains the current instance of this class
	 * @var object
	 */
	static $_instances = null;
	
	/**
	 * Method is called when we need to instantiate this class
	 * 
	 * @param array $id
	 * @param array $options
	 */
	public static function getInstance( $id, $options = array() )
	{
		$id = sanitize_title($id);
		if (!isset(self::$_instances[$id]))
		{
			$options['id'] = $id;
			$class = get_class();
			self::$_instances[$id] =& new $class($options);
		}
		return self::$_instances[$id];
	}
}

/**
 * API function makes it quick and painless to request a specific administrative option
 * from the admin settings page desired.
 * 
 * @param string $object_id
 * @param string $property
 * @param mixed $default
 * @param boolean $single
 */
function redrokk_admin_option( $object_id, $property, $default = false, $single = true )
{
	return redrokk_admin_class::getInstance($object_id)->getOption($property, $default, $single);
}
endif;