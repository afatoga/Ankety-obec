function vm_changeProjectDisplayMode(el) {

    var projectItems = document.querySelectorAll('.vm_project-item');

    if (el.dataset.displayMode === "blocks") {
    el.dataset.displayMode = 'list';
    

    for(var i=0; i<projectItems.length; i++) {
        projectItems[i].classList.remove('col-lg-6');
        projectItems[i].classList.remove('col-12');
        projectItems[i].classList.add('w-100');
    }

    document.querySelector('.vm_project-container').classList.add('flex-column');
    document.querySelector('.vm_project-container').classList.remove('flex-wrap');
    }

    else {
        el.dataset.displayMode = 'blocks';
        for(var i=0; i<projectItems.length; i++) {
            projectItems[i].classList.add('col-lg-6');
            projectItems[i].classList.add('col-12');
            projectItems[i].classList.remove('w-100');
        }
        document.querySelector('.vm_project-container').classList.add('flex-wrap');
        document.querySelector('.vm_project-container').classList.remove('flex-column');
    }
}