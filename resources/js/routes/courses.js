import CourseEdit from "../components/Courses/CourseEdit";
import CourseShow from "../components/Courses/CourseShow";
import LogLesson from "../components/Lessons/LogLesson";
import LessonLogShow from "../components/Lessons/LessonLogShow";
import ActiveCoursesIndex from "../components/Courses/ActiveCoursesIndex";
import LoggedLessons from "../components/Courses/LoggedLessons";
import LoggedLesson from "../components/Courses/LoggedLesson";
import DueLogging from "../components/Courses/DueLogging";
import DueLoggingLesson from "../components/Courses/DueLoggingLesson";

export default [
    { path: "/courses/:id/edit", component: CourseEdit },
    { path: "/courses/:id", component: CourseShow },
    { path: "/lessons/:id/log", component: LogLesson },
    { path: "/my-lesson-logs/:id", component: LessonLogShow },
    { path: "/logged-lessons", component: LoggedLessons },
    { path: "/logged-lessons/:id", component: LoggedLesson },
    { path: "/active-courses", component: ActiveCoursesIndex },
    { path: "/due-logging-lessons", component: DueLogging },
    { path: "/due-logging-lessons/:id", component: DueLoggingLesson }
];
